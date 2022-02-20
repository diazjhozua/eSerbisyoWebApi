<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackTypeSeeder extends Seeder
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

        $dateTime = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

        //id 1-3
        DB::table('types')->insert([
            'name' => "Disaster Preparedness",
            'model_type' => 'Feedback',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Barangay Covid 19 management",
            'model_type' => 'Feedback',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Anti-Rabies Plan",
            'model_type' => 'Feedback',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);
    }
}
