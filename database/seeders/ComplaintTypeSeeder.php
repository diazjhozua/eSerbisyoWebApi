<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintTypeSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        activity()->disableLogging();
        //id 31-35
        // foreach (range(1,5) as $index) {
        //     $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
        //     DB::table('types')->insert([
        //         'name' => $faker->realText($maxNbChars = 30, $indexSize = 3),
        //         'model_type' => 'Complaint',
        //         'created_at' => $date,
        //         'updated_at' => $date,
        //     ]);
        // }

        $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
        DB::table('types')->insert([
            'name' => "Noise Problem",
            'model_type' => 'Complaint',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => 'Family Dispute',
            'model_type' => 'Complaint',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => 'Land and Ownership',
            'model_type' => 'Complaint',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => 'Neighborhood Problem',
            'model_type' => 'Complaint',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => 'Vehicle Accident',
            'model_type' => 'Complaint',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

    }
}
