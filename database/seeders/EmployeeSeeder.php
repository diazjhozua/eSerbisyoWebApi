<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        DB::table('employees')->insert([
            'name' => $faker->name,
            'term_id' => 1,
            'position_id' => 1,
            'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        foreach (range(1,7) as $index) {
            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => 1,
                'position_id' => 2,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
                'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            ]);
        }

        DB::table('employees')->insert([
            'name' => $faker->name,
            'term_id' => 1,
            'position_id' => 3,
            'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        DB::table('employees')->insert([
            'name' => $faker->name,
            'term_id' => 1,
            'position_id' => 4,
            'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        DB::table('employees')->insert([
            'name' => $faker->name,
            'term_id' => 1,
            'position_id' => 5,
            'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        foreach (range(1,7) as $index) {
            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => 1,
                'position_id' => 6,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
                'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
             ]);
        }
    }
}
