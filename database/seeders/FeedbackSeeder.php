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
            $typeID = $faker->numberBetween(0, 3);
            $polarity = ['Positive', 'Neutral', 'Negative'];
            $status = ['Pending', 'Ignored', 'Noted'];

            $customType = null;

            if ($typeID === 0) {
                $typeID = NULL;
                $customType = $faker->realText($maxNbChars = 30, $indexSize = 3);
            }

            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
	        DB::table('feedbacks')->insert([
                'user_id' =>  $userRandomID,
                'type_id' => $typeID,
                'custom_type' => $customType,
                'polarity' => $polarity[array_rand($polarity)],
                'message' =>  $faker->paragraph($nbSentences = 4, $variableNbSentences = true),
                'status' => $status[array_rand($status)],
                'is_anonymous' => $faker->numberBetween(0, 1),
                'created_at' => $date,
                'updated_at' => $date,
	        ]);
        }
    }
}
