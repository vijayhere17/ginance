<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ROIPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\ROIPackage::insert([

        [
            'min_amount' => 10,
            'max_amount' => 50,
            'cap_multiplier' => 2.00,
            'status' => 1,
        ],

        [
            'min_amount' => 51,
            'max_amount' => 499,
            'cap_multiplier' => 2.25,
            'status' => 1,
        ],

        [
            'min_amount' => 500,
            'max_amount' => 999,
            'cap_multiplier' => 2.50,
            'status' => 1,
        ],

        [
            'min_amount' => 1000,
            'max_amount' => 2499,
            'cap_multiplier' => 2.75,
            'status' => 1,
        ],

        [
            'min_amount' => 2500,
            'max_amount' => 4999,
            'cap_multiplier' => 3.00,
            'status' => 1,
        ],

        [
            'min_amount' => 5000,
            'max_amount' => 9999,
            'cap_multiplier' => 3.25,
            'status' => 1,
        ],

        [
            'min_amount' => 10000,
            'max_amount' => 14999,
            'cap_multiplier' => 3.50,
            'status' => 1,
        ],

        [
            'min_amount' => 15000,
            'max_amount' => 19999,
            'cap_multiplier' => 3.75,
            'status' => 1,
        ],

        [
            'min_amount' => 20000,
            'max_amount' => null,
            'cap_multiplier' => 4.00,
            'status' => 1,
        ],

    ]);
}
}
