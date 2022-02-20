<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdinanceTypeSeeder extends Seeder
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
        //id 13-20

        //1
        DB::table('types')->insert([
            'name' => "Agriculture, Fisheries, Aquatic, Environment & Natural Resources",
            'model_type' => 'Ordinance',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        //2
        DB::table('types')->insert([
            'name' => "Appropriation",
            'model_type' => 'Ordinance',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        //3
        DB::table('types')->insert([
            'name' => "Barangay Affairs",
            'model_type' => 'Ordinance',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        //4
        DB::table('types')->insert([
            'name' => "Blue Ribbon",
            'model_type' => 'Ordinance',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        //5
        DB::table('types')->insert([
            'name' => "Communication & Public Information",
            'model_type' => 'Ordinance',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        //6
        DB::table('types')->insert([
            'name' => "Disaster Risk Reduction",
            'model_type' => 'Ordinance',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        //7
        DB::table('types')->insert([
            'name' => "Education",
            'model_type' => 'Ordinance',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        //8
        DB::table('types')->insert([
            'name' => "Engineering, Public Works & Infrastructure",
            'model_type' => 'Ordinance',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);
    }
}
