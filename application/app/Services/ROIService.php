<?php

namespace App\Services;

use App\Models\ROIPackage;
use App\Models\ROICycle;
use App\Models\UserStaked;
use App\Models\UserInvestment;
use App\Models\ROIIncomeHistory;
use Illuminate\Support\Facades\DB;


class ROIService
{
    /**
     * Get ROI Package based on investment amount.
     */
    public function getPackage($amount)
    {
        return ROIPackage::where('status', 1)
            ->where('min_amount', '<=', $amount)
            ->where(function ($query) use ($amount) {
                $query->where('max_amount', '>=', $amount)
                      ->orWhereNull('max_amount');
            })
            ->first();
    }

    /**
     * Get First ROI Cycle.
     */
    public function getFirstCycle()
    {
        return ROICycle::where('cycle_no', 1)
            ->where('status', 1)
            ->first();
    }

   
    /**
     * Calculate Maximum Income.
     */
    public function calculateMaximumIncome($amount, $capMultiplier)
    {
        return round($amount * $capMultiplier, 2);
    }

    /**
     * Validate Investment Amount.
     */
    public function validateInvestmentAmount($amount)
    {
        if (!is_numeric($amount)) {
            throw new \Exception('Investment amount must be numeric.');
        }

        if ($amount <= 0) {
            throw new \Exception('Investment amount must be greater than zero.');
        }

        $minimumPackage = ROIPackage::orderBy('min_amount')->first();

        if (!$minimumPackage) {
            throw new \Exception('ROI packages are not configured.');
        }

        if ($amount < $minimumPackage->min_amount) {
            throw new \Exception(
                'Minimum investment amount is ' . $minimumPackage->min_amount
            );
        }

        return true;
    }

    /**
     * Create New ROI Investment.
     */
   /**
 * Initialize ROI for newly created stake.
 */
/**
 * Create ROI Investment from Staked User.
 */
/**
 * Create ROI investment from stake.
 */
public function createInvestment($userStakedId)
{
    $stake = UserStaked::find($userStakedId);

    if (!$stake) {
        throw new \Exception('Stake not found.');
    }

    // Already created
    $exists = UserInvestment::where('staked_user_id', $stake->id)->first();

    if ($exists) {
        return $exists;
    }

    // Validate Amount
    $this->validateInvestmentAmount($stake->paid_amount);

    // Find Package
    $package = $this->getPackage($stake->paid_amount);

    if (!$package) {
        throw new \Exception('ROI Package not found.');
    }

    // First Cycle
    $cycle = $this->getFirstCycle();

    if (!$cycle) {
        throw new \Exception('ROI Cycle not found.');
    }

    // Generate Investment Number
    $nextId = (UserInvestment::max('id') ?? 0) + 1;

    $investmentNo = 'ROI' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

    // Maximum Income
    $maximumIncome = $this->calculateMaximumIncome(
        $stake->paid_amount,
        $package->cap_multiplier
    );

    return UserInvestment::create([

        'user_id' => $stake->member_id,

        'staked_user_id' => $stake->id,

        'investment_no' => $investmentNo,

        'amount' => $stake->paid_amount,

        'package_id' => $package->id,

        'cap_multiplier' => $package->cap_multiplier,

        'maximum_income' => $maximumIncome,

        'total_roi_paid' => 0,

        'remaining_income' => $maximumIncome,

        'current_cycle' => $cycle->cycle_no,

        'current_roi_percent' => $cycle->daily_roi,

        'cycle_start_date' => now()->toDateString(),

        'cycle_end_date' => $cycle->is_lifetime
            ? null
            : now()->addDays($cycle->duration_days)->toDateString(),

        'days_completed' => 0,

        'status' => 'active',
    ]);
}

public function processDailyROI()
{
    $investments = UserInvestment::where('status', 'active')
        ->where('remaining_income', '>', 0)
        ->get();

foreach ($investments as $investment) {

if (
    ROIIncomeHistory::where('investment_id', $investment->id)
        ->whereDate('roi_date', today())
        ->exists()
) {
    continue;
}

    DB::transaction(function () use ($investment) {

    $todayROI = round(
        ($investment->amount * $investment->current_roi_percent) / 100,
        2
    );

    $payableROI = min($todayROI, $investment->remaining_income);

    ROIIncomeHistory::create([
        'investment_id' => $investment->id,
        'user_id' => $investment->user_id,
        'roi_percent' => $investment->current_roi_percent,
        'roi_amount' => $payableROI,
        'cycle_no' => $investment->current_cycle,
        'roi_date' => now()->toDateString(),
        'remarks' => 'Daily ROI',
    ]);

    $investment->increment('total_roi_paid', $payableROI);

    $investment->decrement('remaining_income', $payableROI);

    $investment->increment('days_completed');

});

}
}
}