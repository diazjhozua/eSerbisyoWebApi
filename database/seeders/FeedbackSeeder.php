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
        activity()->disableLogging();
        $faker = \Faker\Factory::create();

        // $polarity = ['Positive', 'Neutral', 'Negative'];
        $status = ['Pending', 'Ignored', 'Noted'];


        foreach (range(1,100) as $index)
        {
            $userRandomID = $faker->numberBetween(1, 63);
            $typeID = $faker->numberBetween(0, 3);
            $customType = null;

            if ($typeID === 0) {
                $typeID = NULL;
                $customType = $faker->realText($maxNbChars = 30, $indexSize = 3);
            }

            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null);

            $admin_message = NULL;
            $status_name =  $status[array_rand($status)];
            if ($status_name === 'Noted' ) {
                $admin_message = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
            }


	        DB::table('feedbacks')->insert([
                'user_id' =>  $userRandomID,
                'type_id' => $typeID,
                'custom_type' => $customType,
                'rating' => $faker->numberBetween(1, 5),
                'message' =>  $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
                'status' => $status_name,
                'admin_respond' => $admin_message,
                'is_anonymous' => $faker->numberBetween(0, 1),
                'created_at' => $date,
                'updated_at' => $date,
	        ]);
        }
    }
}
