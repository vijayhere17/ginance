<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds return_date to reward_achiever for weekly salary scheduling,
 * matching the existing salary_achiever.return_date pattern.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reward_achiever')) {
            return;
        }

        if (!Schema::hasColumn('reward_achiever', 'return_date')) {
            Schema::table('reward_achiever', function (Blueprint $table) {
                $table->date('return_date')->nullable()->after('achieve_date');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('reward_achiever') && Schema::hasColumn('reward_achiever', 'return_date')) {
            Schema::table('reward_achiever', function (Blueprint $table) {
                $table->dropColumn('return_date');
            });
        }
    }
};
