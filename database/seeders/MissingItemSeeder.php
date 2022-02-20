<?php

namespace Database\Seeders;

use App\Models\MissingItem;
use Illuminate\Database\Seeder;

class MissingItemSeeder extends Seeder
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


        $statusArr = ['Pending', 'Denied', 'Approved', 'Resolved'];
        $report_type = ['Missing', 'Found'];

        foreach (range(1,100) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppLostAndFounds', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\missing-pictures', false);
            $file_path = 'missing-pictures/'.$picture_name;

            $credentials_name = $faker->file($sourceDir = 'C:\Project Assets\AppUsers', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\credentials', false);
            $credentials_file_path = 'credentials/'.$credentials_name;

            $admin_message = null;

            $status = $statusArr[array_rand($statusArr)];

            if ($status != 'Pending') {
                $admin_message = $faker->realText($maxNbChars = 50, $indexSize = 1);
            }

            $userID = $faker->numberBetween(1, 100);

            $lostAndFound = MissingItem::create([
                'user_id' => $userID,
                'contact_user_id' => $userID,
                'item' => $faker->sentence($nbWords = 3, $variableNbWords = true),
                'last_seen' => $faker->streetName(),
                'description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
                'email' => $faker->lastName.$faker->email,
                'phone_no' => $faker->phoneNumber,
                'status' => $status,
                'admin_message' => $admin_message,
                'report_type' => $report_type[array_rand($report_type)],
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'credential_name' => $credentials_name,
                'credential_file_path' => $credentials_file_path,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
                'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
            ]);

            $commentCount = $faker->numberBetween(1,20);

            foreach (range(1, $commentCount) as $index) {
                $lostAndFound->comments()->create([
                    'user_id' => $faker->numberBetween(1,37),
                    'body' => $faker->realText($maxNbChars = 100, $indexSize = 3)
                ]);
            }

        }
    }
}
