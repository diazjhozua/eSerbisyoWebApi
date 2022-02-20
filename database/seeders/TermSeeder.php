<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        activity()->disableLogging();
        $faker = \Faker\Factory::create();

        DB::table('terms')->insert([
            'name' => "Celso Dioko Term",
            'year_start' => 2018,
            'year_end' => 2022,
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
        ]);

        DB::table('terms')->insert([
            'name' => "Reiner Bulos Term",
            'year_start' => 2022,
            'year_end' => 2026,
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
        ]);
    }
}
