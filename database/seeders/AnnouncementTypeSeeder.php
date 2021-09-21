<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        //31-35
        foreach (range(1,5) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            DB::table('types')->insert([
                'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'model_type' => 'Announcement',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
