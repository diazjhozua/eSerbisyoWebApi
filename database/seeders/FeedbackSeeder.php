<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach (range(1,30) as $index)
        {
            $userRandomID = $faker->numberBetween(1, 19);
            $feedbackTypeID = $faker->numberBetween(1, 3);
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
	        DB::table('feedbacks')->insert([
                'user_id' =>  $userRandomID,
                'feedback_type_id' => $feedbackTypeID,
                'message' =>  $faker->paragraph($nbSentences = 4, $variableNbSentences = true),
                'is_anonymous' => $faker->numberBetween(0, 1),
                'created_at' => $date,
                'updated_at' => $date,
	        ]);
        }
    }
}
