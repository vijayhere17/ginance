<?php

// Level Income ("ROI to ROI") percentages by level band
$level_income_ladder = [
    1 => 10,
    2 => 5,
    3 => 5,
    4 => 4,
    5 => 4,
    6 => 3,
    7 => 3,
    8 => 2,
    9 => 2,
];

foreach (range(10, 20) as $level) {
    $level_income_ladder[$level] = 1;
}

foreach (range(21, 50) as $level) {
    $level_income_ladder[$level] = 0.50;
}

foreach (range(51, 100) as $level) {
    $level_income_ladder[$level] = 0.25;
}

foreach (range(101, 200) as $level) {
    $level_income_ladder[$level] = 0.10;
}

return [

    // Direct Sponsor Income - % of investment amount, level => percent
    'direct_sponsor_levels' => [
        1 => 3,
        2 => 2,
        3 => 1,
    ],

    // Level Income ("ROI to ROI") - % of each daily ROI payout, level => percent
    'level_income_ladder' => $level_income_ladder,

    // Max upline depth for Level Income payouts
    'level_income_max_depth' => 200,

    // Active-direct requirements by level band (inclusive).
    // required_directs = 'level' means the member needs that many active directs equal to the level number.
    'level_income_qualification' => [
        ['from' => 1,   'to' => 9,   'required_directs' => 'level'],
        ['from' => 10,  'to' => 20,  'required_directs' => 5],
        ['from' => 21,  'to' => 50,  'required_directs' => 10],
        ['from' => 51,  'to' => 100, 'required_directs' => 15],
        ['from' => 101, 'to' => 200, 'required_directs' => 20],
    ],

    // Booster Income - directs sponsored within 48hrs of own activation => extra daily ROI percent
    'booster_tiers' => [
        10 => 0.25,
        7 => 0.20,
        5 => 0.15,
        3 => 0.10,
    ],

    'booster_window_hours' => 48,

    // Rewards (turnover milestone) leg split
    'reward_leg1_percent' => 40,
    'reward_leg2_percent' => 40,
    'reward_leg3_percent' => 20,

    // Earning cap multipliers
    'working_cap_multiplier' => 3,
    'non_working_cap_multiplier' => 2,

    // Withdrawal charge tiers (income withdrawals), days elapsed => charge percent
    'withdrawal_charge_tiers' => [
        60 => 0,
        30 => 5,
        0 => 10,
    ],

    // Capital withdrawal
    'capital_withdrawal_charge_percent' => 30,
    'capital_withdrawal_window_months' => 8,

    // Set to true to re-enable the legacy rank-based Salary cron (runSalaryAchiever/runSalaryEarning).
    // Left in place, not deleted, per business decision to replace Salary with Turnover Reward income.
    'legacy_salary_enabled' => false,

    // earning_type allocations used across the app (documentation only, not read programmatically)
    // 1 = Direct Sponsor Income, 2 = Daily ROI, 3 = Cashback, 4 = Level Income,
    // 5 = Legacy Salary (dormant), 6 = DMC Leadership, 7 = Turnover Reward, 8 = Booster Income, 9 = Life Time Reward
];
