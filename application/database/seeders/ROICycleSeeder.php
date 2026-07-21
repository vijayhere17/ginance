<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ROICycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\ROICycle::insert([

        [
            'cycle_no' => 1,
            'daily_roi' => 3.00,
            'duration_days' => 15,
            'is_lifetime' => 0,
            'status' => 1,
        ],

        [
            'cycle_no' => 2,
            'daily_roi' => 2.75,
            'duration_days' => 20,
            'is_lifetime' => 0,
            'status' => 1,
        ],

        [
            'cycle_no' => 3,
            'daily_roi' => 2.50,
            'duration_days' => 25,
            'is_lifetime' => 0,
            'status' => 1,
        ],

        [
            'cycle_no' => 4,
            'daily_roi' => 2.25,
            'duration_days' => 30,
            'is_lifetime' => 0,
            'status' => 1,
        ],

        [
            'cycle_no' => 5,
            'daily_roi' => 2.00,
            'duration_days' => 30,
            'is_lifetime' => 0,
            'status' => 1,
        ],

        [
            'cycle_no' => 6,
            'daily_roi' => 1.75,
            'duration_days' => 30,
            'is_lifetime' => 0,
            'status' => 1,
        ],

        [
            'cycle_no' => 7,
            'daily_roi' => 1.50,
            'duration_days' => 30,
            'is_lifetime' => 0,
            'status' => 1,
        ],

        [
            'cycle_no' => 8,
            'daily_roi' => 1.25,
            'duration_days' => 30,
            'is_lifetime' => 0,
            'status' => 1,
        ],

        [
            'cycle_no' => 9,
            'daily_roi' => 1.00,
            'duration_days' => 30,
            'is_lifetime' => 0,
            'status' => 1,
        ],

        [
            'cycle_no' => 10,
            'daily_roi' => 0.50,
            'duration_days' => null,
            'is_lifetime' => 1,
            'status' => 1,
        ],

    ]);
}
}
