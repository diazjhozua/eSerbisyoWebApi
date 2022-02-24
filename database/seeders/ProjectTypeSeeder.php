<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectTypeSeeder extends Seeder
{

    public function run()
    {
        activity()->disableLogging();
        $faker = \Faker\Factory::create();

        //id 21-25
        // foreach (range(1,5) as $index) {
        //     $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
        //     DB::table('types')->insert([
        //         'name' => $faker->realText($maxNbChars = 30, $indexSize = 3),
        //         'model_type' => 'Project',
        //         'created_at' => $date,
        //         'updated_at' => $date,
        //     ]);
        // }

        $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
        DB::table('types')->insert([
            'name' => "Building Infastructure",
            'model_type' => 'Project',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => "Health",
            'model_type' => 'Project',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => 'Road Projects',
            'model_type' => 'Project',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => 'Drainage system',
            'model_type' => 'Project',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        DB::table('types')->insert([
            'name' => 'Feeding Program',
            'model_type' => 'Project',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
