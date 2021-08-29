<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach (range(1,20) as $index) {
            \DB::table('announcements')->insert([
                'announcement_type_id' => $faker->numberBetween(1,5),
                'title' => $faker->words($nb = 3, $asText = true) ,
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            ]);
        }
    }
}
