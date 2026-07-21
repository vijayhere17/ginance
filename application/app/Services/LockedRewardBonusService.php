<?php

namespace App\Services;

use App\Http\Controllers\Users\EarningWalletController;
use App\Models\User;
use App\Models\UserStaked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LockedRewardBonusService
{
    /**
     * Allocate $1000 locked reward once on a member's first package activation.
     */
    public function allocateOnFirstActivation(User $member)
    {
        // Already allocated once - never allocate again.
        if ($member->reward_lock_date != null) {
            return false;
        }

        $amount = (float) config('income.locked_reward_bonus_amount', 1000);
        $days = (int) config('income.locked_reward_validity_days', 30);
        $now = date('Y-m-d H:i:s');

        $member->locked_reward_bonus = $amount;
        $member->unlocked_reward_bonus = (float) ($member->unlocked_reward_bonus ?? 0);
        $member->expired_reward_bonus = (float) ($member->expired_reward_bonus ?? 0);
        $member->reward_lock_date = $now;
        $member->reward_expiry_date = date('Y-m-d H:i:s', strtotime($now.' + '.$days.' days'));
        $member->save();

        return true;
    }

    /**
     * Unlock 10% of a direct referral's activation amount for the sponsor.
     * Caps at remaining locked_reward_bonus. Credits earning wallet with no deductions.
     * Marks the stake row so the same activation cannot unlock twice.
     */
    public function unlockForDirectSponsor(User $sponsor, $activationAmount, UserStaked $stakeLog, $fromUsername = '')
    {
        if ($stakeLog->locked_reward_unlock_paid) {
            return 0;
        }

        $locked = (float) ($sponsor->locked_reward_bonus ?? 0);
        if ($locked <= 0) {
            $stakeLog->locked_reward_unlock_paid = 1;
            $stakeLog->save();
            return 0;
        }

        // Expiry check - do not unlock after reward_expiry_date.
        if ($sponsor->reward_expiry_date != null && strtotime($sponsor->reward_expiry_date) < strtotime(date('Y-m-d H:i:s'))) {
            $stakeLog->locked_reward_unlock_paid = 1;
            $stakeLog->save();
            return 0;
        }

        $percent = (float) config('income.locked_reward_unlock_percent', 10);
        $unlock = ($activationAmount * $percent) / 100;

        if ($unlock > $locked) {
            $unlock = $locked;
        }

        $unlock = round($unlock, 4);

        if ($unlock <= 0) {
            $stakeLog->locked_reward_unlock_paid = 1;
            $stakeLog->save();
            return 0;
        }

        try {
            DB::beginTransaction();

            // Re-lock sponsor row to avoid race on concurrent activations.
            $sponsor = User::where('id', '=', $sponsor->id)->lockForUpdate()->first();
            $stakeLog = UserStaked::where('id', '=', $stakeLog->id)->lockForUpdate()->first();

            if ($stakeLog->locked_reward_unlock_paid) {
                DB::rollBack();
                return 0;
            }

            $locked = (float) ($sponsor->locked_reward_bonus ?? 0);
            if ($locked <= 0) {
                $stakeLog->locked_reward_unlock_paid = 1;
                $stakeLog->save();
                DB::commit();
                return 0;
            }

            $unlock = ($activationAmount * $percent) / 100;
            if ($unlock > $locked) {
                $unlock = $locked;
            }
            $unlock = round($unlock, 4);

            if ($unlock <= 0) {
                $stakeLog->locked_reward_unlock_paid = 1;
                $stakeLog->save();
                DB::commit();
                return 0;
            }

            $sponsor->locked_reward_bonus = round($locked - $unlock, 4);
            $sponsor->unlocked_reward_bonus = round(((float) $sponsor->unlocked_reward_bonus) + $unlock, 4);
            $sponsor->save();

            $stakeLog->locked_reward_unlock_paid = 1;
            $stakeLog->save();

            $walletCon = app(EarningWalletController::class);
            $earning_type = (int) config('income.locked_reward_earning_type', 10);
            $from = $fromUsername !== '' ? obscureAddress($fromUsername) : '';
            $description = 'Locked Reward Unlock From '.$from;

            // txn_type 1 = full credit, zero admin/system charge (no TDS / fee).
            $walletCon->addearningwalletlog(
                $sponsor->id,
                1,
                $earning_type,
                $description,
                $unlock,
                0,
                0,
                date('Y-m-d H:i:s')
            );

            DB::commit();

            return $unlock;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return 0;
        }
    }

    /**
     * Daily expiry: move remaining locked balance to expired_reward_bonus after reward_expiry_date.
     */
    public function runExpiry()
    {
        $now = date('Y-m-d H:i:s');

        $members = User::where('locked_reward_bonus', '>', 0)
            ->whereNotNull('reward_expiry_date')
            ->where('reward_expiry_date', '<', $now)
            ->get();

        foreach ($members as $member) {
            $remaining = (float) $member->locked_reward_bonus;
            if ($remaining <= 0) {
                continue;
            }

            $member->expired_reward_bonus = round(((float) $member->expired_reward_bonus) + $remaining, 4);
            $member->locked_reward_bonus = 0;
            $member->save();
        }
    }
}
