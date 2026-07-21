<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reuses the project's intended reward_achiever table (referenced by RewardController).
 * Creates the table if missing, or adds only the columns required by the
 * Reward Qualification Engine when the table already exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reward_achiever')) {
            Schema::create('reward_achiever', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('member_id');
                $table->unsignedInteger('reward_id');
                $table->unsignedInteger('directs')->default(0);
                $table->unsignedInteger('team_members')->default(0);
                $table->decimal('self_business', 18, 2)->default(0);
                $table->decimal('team_business', 18, 2)->default(0);
                $table->decimal('leg1_business', 18, 2)->default(0);
                $table->decimal('leg2_business', 18, 2)->default(0);
                $table->decimal('leg3_business', 18, 2)->default(0);
                $table->decimal('weekly_salary', 18, 2)->default(0);
                $table->dateTime('achieve_date')->nullable();
                $table->date('return_date')->nullable();
                $table->timestamps();

                $table->unique(['member_id', 'reward_id'], 'reward_achiever_member_reward_unique');
                $table->index('member_id');
            });

            return;
        }

        $columns = [
            'member_id' => function (Blueprint $table) {
                $table->unsignedBigInteger('member_id')->after('id');
            },
            'reward_id' => function (Blueprint $table) {
                $table->unsignedInteger('reward_id')->after('member_id');
            },
            'directs' => function (Blueprint $table) {
                $table->unsignedInteger('directs')->default(0);
            },
            'team_members' => function (Blueprint $table) {
                $table->unsignedInteger('team_members')->default(0);
            },
            'self_business' => function (Blueprint $table) {
                $table->decimal('self_business', 18, 2)->default(0);
            },
            'team_business' => function (Blueprint $table) {
                $table->decimal('team_business', 18, 2)->default(0);
            },
            'leg1_business' => function (Blueprint $table) {
                $table->decimal('leg1_business', 18, 2)->default(0);
            },
            'leg2_business' => function (Blueprint $table) {
                $table->decimal('leg2_business', 18, 2)->default(0);
            },
            'leg3_business' => function (Blueprint $table) {
                $table->decimal('leg3_business', 18, 2)->default(0);
            },
            'weekly_salary' => function (Blueprint $table) {
                $table->decimal('weekly_salary', 18, 2)->default(0);
            },
            'achieve_date' => function (Blueprint $table) {
                $table->dateTime('achieve_date')->nullable();
            },
            'return_date' => function (Blueprint $table) {
                $table->date('return_date')->nullable();
            },
        ];

        foreach ($columns as $column => $callback) {
            if (!Schema::hasColumn('reward_achiever', $column)) {
                Schema::table('reward_achiever', $callback);
            }
        }

        if (!Schema::hasColumn('reward_achiever', 'created_at')) {
            Schema::table('reward_achiever', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Do not drop reward_achiever - it may already have existed in production.
    }
};
