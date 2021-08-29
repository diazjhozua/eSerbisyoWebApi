<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach(range(1,10) as $index) {
            DB::table('projects')->insert([
                'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'cost' => $faker->numberBetween($min = 40000, $max = 10000000),
                'project_start' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'project_end' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'location' => $faker->streetAddress(),
                'is_starting' => true,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
	        ]);
        }

        foreach(range(1,10) as $index) {
            DB::table('projects')->insert([
                'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'cost' => $faker->numberBetween($min = 40000, $max = 10000000),
                'project_start' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'project_end' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'location' => $faker->streetAddress(),
                'is_starting' => false,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
	        ]);
        }
    }
}
