<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
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

        DB::table('positions')->insert([
            'ranking' => '1',
            'name' => 'Barangay Captain',
            'job_description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
        ]);

        DB::table('positions')->insert([
            'ranking' => '2',
            'name' => 'Barangay Kagawad',
            'job_description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
        ]);

        DB::table('positions')->insert([
            'ranking' => '3',
            'name' => 'Barangay Secretary',
            'job_description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
        ]);

        DB::table('positions')->insert([
            'ranking' => '4',
            'name' => 'Barangay Treasurer',
            'job_description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
        ]);

        DB::table('positions')->insert([
            'ranking' => '5',
            'name' => 'SK Chairman',
            'job_description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
        ]);

        DB::table('positions')->insert([
            'ranking' => '6',
            'name' => 'SK Kagawad',
            'job_description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
        ]);
    }
}
