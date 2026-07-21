<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds Locked Reward Bonus columns to users and a one-time unlock flag on staked_users.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            $userColumns = [
                'locked_reward_bonus' => function (Blueprint $table) {
                    $table->decimal('locked_reward_bonus', 18, 2)->default(0);
                },
                'unlocked_reward_bonus' => function (Blueprint $table) {
                    $table->decimal('unlocked_reward_bonus', 18, 2)->default(0);
                },
                'expired_reward_bonus' => function (Blueprint $table) {
                    $table->decimal('expired_reward_bonus', 18, 2)->default(0);
                },
                'reward_lock_date' => function (Blueprint $table) {
                    $table->dateTime('reward_lock_date')->nullable();
                },
                'reward_expiry_date' => function (Blueprint $table) {
                    $table->dateTime('reward_expiry_date')->nullable();
                },
            ];

            foreach ($userColumns as $column => $callback) {
                if (!Schema::hasColumn('users', $column)) {
                    Schema::table('users', $callback);
                }
            }
        }

        if (Schema::hasTable('staked_users') && !Schema::hasColumn('staked_users', 'locked_reward_unlock_paid')) {
            Schema::table('staked_users', function (Blueprint $table) {
                $table->unsignedTinyInteger('locked_reward_unlock_paid')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            $drop = [];
            foreach (['locked_reward_bonus', 'unlocked_reward_bonus', 'expired_reward_bonus', 'reward_lock_date', 'reward_expiry_date'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $drop[] = $column;
                }
            }
            if (!empty($drop)) {
                Schema::table('users', function (Blueprint $table) use ($drop) {
                    $table->dropColumn($drop);
                });
            }
        }

        if (Schema::hasTable('staked_users') && Schema::hasColumn('staked_users', 'locked_reward_unlock_paid')) {
            Schema::table('staked_users', function (Blueprint $table) {
                $table->dropColumn('locked_reward_unlock_paid');
            });
        }
    }
};
