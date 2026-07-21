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

    // Reward Qualification Engine - top-3 direct leg split (40:30:30)
    'reward_qualification_leg1_percent' => 40,
    'reward_qualification_leg2_percent' => 30,
    'reward_qualification_leg3_percent' => 30,

    // Reward Qualification Engine - level => requirements
    // weekly_salary is paid via SalaryController::runRewardSalaryEarning when today >= return_date
    'reward_qualification_levels' => [
        1 => [
            'direct' => 5,
            'team_members' => 20,
            'self_business' => 100,
            'team_business' => 5000,
            'weekly_salary' => 10,
        ],
        2 => [
            'direct' => 6,
            'team_members' => 50,
            'self_business' => 200,
            'team_business' => 12000,
            'weekly_salary' => 20,
        ],
        3 => [
            'direct' => 7,
            'team_members' => 100,
            'self_business' => 300,
            'team_business' => 20000,
            'weekly_salary' => 50,
        ],
        4 => [
            'direct' => 8,
            'team_members' => 200,
            'self_business' => 500,
            'team_business' => 50000,
            'weekly_salary' => 100,
        ],
        5 => [
            'direct' => 9,
            'team_members' => 500,
            'self_business' => 700,
            'team_business' => 100000,
            'weekly_salary' => 200,
        ],
        6 => [
            'direct' => 10,
            'team_members' => 1000,
            'self_business' => 1000,
            'team_business' => 250000,
            'weekly_salary' => 300,
        ],
        7 => [
            'direct' => 14,
            'team_members' => 2000,
            'self_business' => 1500,
            'team_business' => 500000,
            'weekly_salary' => 500,
        ],
    ],

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

    // Registration fee (signup only - does not affect package activation amounts)
    'registration_amount' => 1,

    // Locked Reward Bonus (allocated once on first package activation)
    'locked_reward_bonus_amount' => 1000,
    'locked_reward_unlock_percent' => 10,
    'locked_reward_validity_days' => 30,
    // New earning_type for unlocked locked-reward credits (full amount, no charges)
    'locked_reward_earning_type' => 10,

    // Set to true to re-enable the legacy rank-based Salary cron (runSalaryAchiever/runSalaryEarning).
    // Left in place, not deleted, per business decision to replace Salary with Turnover Reward income.
    'legacy_salary_enabled' => false,

    // Wallet earning_type used by Weekly Reward Salary (default 5 = Salary / Salary Bonus).
    // No dedicated Reward Salary type exists in the project; change here if a new type is approved.
    'reward_weekly_salary_earning_type' => 5,

    // earning_type allocations used across the app (documentation only, not read programmatically)
    // 0 = Withdrawal / admin credit-debit (non-income)
    // 1 = Direct Sponsor Income
    // 2 = Daily ROI
    // 3 = Cashback
    // 4 = Level Income (ROI on ROI) [also used historically by Binary matching]
    // 5 = Salary / Potential Bonus (legacy) — also used for Reward Weekly Salary
    // 6 = DMC Leadership
    // 7 = Turnover Reward
    // 8 = Booster Income
    // 9 = Life Time Reward
    // 10 = Locked Reward Unlock
];
