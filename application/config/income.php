<?php

return [

    // Direct Sponsor Income - % of investment amount, level => percent
    'direct_sponsor_levels' => [
        1 => 3,
        2 => 2,
        3 => 1,
    ],

    // Level Income ("ROI to ROI") - % of each daily ROI payout, level => percent
    'level_income_ladder' => [
        1 => 15,
        2 => 10,
        3 => 5,
        4 => 3,
        5 => 2,
        6 => 1,
        7 => 1,
        8 => 1,
        9 => 1,
        10 => 1,
        11 => 0.5,
        12 => 0.5,
        13 => 0.5,
        14 => 0.5,
        15 => 0.5,
        16 => 0.5,
        17 => 0.5,
        18 => 0.5,
        19 => 0.5,
        20 => 0.5,
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
];
