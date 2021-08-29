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
        $faker = \Faker\Factory::create();

        DB::table('feedback_types')->insert([
            'type' => "Compliments",
            'created_at' => $faker->dateTime,
        ]);

        DB::table('feedback_types')->insert([
            'type' => "Recommendation",
            'created_at' => $faker->dateTime,
        ]);

        DB::table('feedback_types')->insert([
            'type' => "Complaint",
            'created_at' => $faker->dateTime,
        ]);
    }
}
