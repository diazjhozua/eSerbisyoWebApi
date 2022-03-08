<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
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
        $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
        $statusArr = ['Pending', 'Noted'];
        foreach (range(1,100) as $id) {
            $userID = $faker->numberBetween(1, 63);
            $status = $statusArr[array_rand($statusArr)];
            DB::table('inquiries')->insert([
                'user_id' => $userID,
                'about' => $faker->realText($maxNbChars = 15, $indexSize = 1),
                'message' => $faker->realText($maxNbChars = 500, $indexSize = 3),
                'status' => $status,
                'admin_message' => $status == 'Noted' ? $faker->realText($maxNbChars = 100, $indexSize = 3) : NULL,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }


}
