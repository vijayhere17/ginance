<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds Reward Qualification criteria columns to existing reward_master
 * so the Reward Achievement page can display requirements from the DB
 * (not hardcoded in Blade).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reward_master')) {
            Schema::create('reward_master', function (Blueprint $table) {
                $table->id();
                $table->string('reward')->nullable();
                $table->string('img')->nullable();
                $table->unsignedInteger('main_leg')->default(40);
                $table->unsignedInteger('other_leg')->default(60);
                $table->unsignedInteger('direct')->default(0);
                $table->unsignedInteger('team_members')->default(0);
                $table->decimal('self_business', 18, 2)->default(0);
                $table->decimal('team_business', 18, 2)->default(0);
                $table->decimal('weekly_salary', 18, 2)->default(0);
                $table->timestamps();
            });

            return;
        }

        $columns = [
            'direct' => function (Blueprint $table) {
                $table->unsignedInteger('direct')->default(0);
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
            'weekly_salary' => function (Blueprint $table) {
                $table->decimal('weekly_salary', 18, 2)->default(0);
            },
        ];

        foreach ($columns as $column => $callback) {
            if (!Schema::hasColumn('reward_master', $column)) {
                Schema::table('reward_master', $callback);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('reward_master')) {
            return;
        }

        $drop = [];
        foreach (['direct', 'team_members', 'self_business', 'team_business', 'weekly_salary'] as $column) {
            if (Schema::hasColumn('reward_master', $column)) {
                $drop[] = $column;
            }
        }

        if (!empty($drop)) {
            Schema::table('reward_master', function (Blueprint $table) use ($drop) {
                $table->dropColumn($drop);
            });
        }
    }
};
