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

    // earning_type allocations used across the app (documentation only, not read programmatically)
    // 1 = Direct Sponsor Income, 2 = Daily ROI, 3 = Cashback, 4 = Level Income,
    // 5 = Legacy Salary (dormant), 6 = DMC Leadership, 7 = Turnover Reward, 8 = Booster Income, 9 = Life Time Reward,
    // 10 = Locked Reward Unlock
];
