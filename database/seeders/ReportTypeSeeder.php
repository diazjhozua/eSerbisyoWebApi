<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // 26-30
        foreach (range(1,5) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            DB::table('types')->insert([
                'name' => $faker->realText($maxNbChars = 30, $indexSize = 3),
                'model_type' => 'Report',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
