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
        activity()->disableLogging();
        $faker = \Faker\Factory::create();

        //26-30
        foreach (range(1,5) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            DB::table('types')->insert([
                'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'model_type' => 'Announcement',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        DB::table('types')->insert([
            'name' => "General",
            'model_type' => 'Announcement',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => "Health",
            'model_type' => 'Announcement',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => "Holiday",
            'model_type' => 'Announcement',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => "Traffic Advisory",
            'model_type' => 'Announcement',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => "Covid-19",
            'model_type' => 'Announcement',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
