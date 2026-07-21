<?php

namespace App\Services;

use App\Http\Controllers\Users\DashboardController;
use App\Models\RewardAchiever;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RewardQualificationService
{
    /**
     * Evaluate and mark reward achievements for all active members.
     * Qualification only - no wallet credit / weekly salary payout.
     */
    public function run()
    {
        $levels = config('income.reward_qualification_levels', []);

        if (empty($levels)) {
            return;
        }

        $dashboardCon = app(DashboardController::class);
        $members = User::where('kit_id', '>', 0)->get();

        foreach ($members as $member) {
            $metrics = $this->buildMemberMetrics($member, $dashboardCon);

            foreach ($levels as $reward_id => $requirements) {
                if ($this->hasAchieved($member->id, $reward_id)) {
                    continue;
                }

                if (!$this->qualifies($metrics, $requirements)) {
                    continue;
                }

                $this->markAchieved($member->id, (int) $reward_id, $metrics, $requirements);
            }
        }
    }

    /**
     * Collect qualification metrics using existing project helpers / fields.
     */
    protected function buildMemberMetrics(User $member, DashboardController $dashboardCon)
    {
        $directs = User::where('referral_id', '=', $member->id)->where('kit_id', '>', 0)->count();
        $team_members = (int) $dashboardCon->getDownlineTeam($member->id, 1);
        $legs = $this->getTopThreeDirectLegBusiness($member->id);

        return [
            'directs' => $directs,
            'team_members' => $team_members,
            'self_business' => (float) $member->self_investment,
            'team_business' => (float) $member->team_investment,
            'leg1_business' => $legs['leg1_business'],
            'leg2_business' => $legs['leg2_business'],
            'leg3_business' => $legs['leg3_business'],
        ];
    }

    /**
     * Business under every direct referral leg, sorted desc, top 3 only.
     * Reuses users.team_investment (same source as TurnoverRewardController).
     */
    protected function getTopThreeDirectLegBusiness($member_id)
    {
        $legs = User::where('referral_id', '=', $member_id)
            ->orderByDesc('team_investment')
            ->limit(3)
            ->pluck('team_investment')
            ->values();

        return [
            'leg1_business' => (float) ($legs[0] ?? 0),
            'leg2_business' => (float) ($legs[1] ?? 0),
            'leg3_business' => (float) ($legs[2] ?? 0),
        ];
    }

    /**
     * All conditions must pass, including 40:30:30 on required team business.
     */
    protected function qualifies(array $metrics, array $requirements)
    {
        if ($metrics['directs'] < (int) $requirements['direct']) {
            return false;
        }

        if ($metrics['team_members'] < (int) $requirements['team_members']) {
            return false;
        }

        if ($metrics['self_business'] < (float) $requirements['self_business']) {
            return false;
        }

        $required_team = (float) $requirements['team_business'];

        if ($metrics['team_business'] < $required_team) {
            return false;
        }

        return $this->passesTeamBusinessRatio($metrics, $required_team);
    }

    /**
     * Largest / 2nd / 3rd direct legs must meet configured % of required team business.
     */
    protected function passesTeamBusinessRatio(array $metrics, $required_team)
    {
        $leg1_percent = (float) config('income.reward_qualification_leg1_percent', 40);
        $leg2_percent = (float) config('income.reward_qualification_leg2_percent', 30);
        $leg3_percent = (float) config('income.reward_qualification_leg3_percent', 30);

        $need_leg1 = $required_team * $leg1_percent / 100;
        $need_leg2 = $required_team * $leg2_percent / 100;
        $need_leg3 = $required_team * $leg3_percent / 100;

        return $metrics['leg1_business'] >= $need_leg1
            && $metrics['leg2_business'] >= $need_leg2
            && $metrics['leg3_business'] >= $need_leg3;
    }

    protected function hasAchieved($member_id, $reward_id)
    {
        return RewardAchiever::where('member_id', '=', $member_id)
            ->where('reward_id', '=', $reward_id)
            ->exists();
    }

    /**
     * Persist achievement once. Unique (member_id, reward_id) is the race guard.
     */
    protected function markAchieved($member_id, $reward_id, array $metrics, array $requirements)
    {
        try {
            DB::beginTransaction();

            $achiever = new RewardAchiever;
            $achiever->member_id = $member_id;
            $achiever->reward_id = $reward_id;
            $achiever->directs = $metrics['directs'];
            $achiever->team_members = $metrics['team_members'];
            $achiever->self_business = $metrics['self_business'];
            $achiever->team_business = $metrics['team_business'];
            $achiever->leg1_business = $metrics['leg1_business'];
            $achiever->leg2_business = $metrics['leg2_business'];
            $achiever->leg3_business = $metrics['leg3_business'];
            $achiever->weekly_salary = $requirements['weekly_salary'];
            $achiever->achieve_date = date('Y-m-d H:i:s');
            $achiever->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }
}
