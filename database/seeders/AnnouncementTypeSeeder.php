<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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

        foreach (range(1,5) as $index) {
            \DB::table('announcement_types')->insert([
                'type' => $faker->words($nb = 2, $asText = true),
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            ]);
        }
    }
}
