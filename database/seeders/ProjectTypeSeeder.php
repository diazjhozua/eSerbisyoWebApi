<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $dateTime = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);


        DB::table('types')->insert([
            'name' => "Projecta",
            'model_type' => 'Project',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Projectb",
            'model_type' => 'Project',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Projectc",
            'model_type' => 'Project',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Projectd",
            'model_type' => 'Project',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Projecte",
            'model_type' => 'Project',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Projectf",
            'model_type' => 'Project',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Projectg",
            'model_type' => 'Project',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Projecth",
            'model_type' => 'Project',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

    }
}
