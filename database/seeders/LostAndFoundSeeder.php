<?php

namespace Database\Seeders;

use App\Models\LostAndFound;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LostAndFoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $faker = \Faker\Factory::create();

        $status = ['Pending', 'Denied', 'Approved', 'Resolved'];
        $report_type = ['Missing', 'Found'];

        foreach (range(1,20) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppMissingPersons', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\missing-pictures', false);
            $file_path = 'storage/missing-pictures/'.$picture_name;

            $lostAndFound = LostAndFound::create([
                'user_id' => $faker->numberBetween(1, 19),
                'item' => $faker->sentence($nbWords = 3, $variableNbWords = true),
                'last_seen' => $faker->streetName(),
                'description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
                'contact_information' => $faker->tollFreePhoneNumber(),
                'status' => $status[array_rand($status)],
                'report_type' => $report_type[array_rand($report_type)],
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $date,
                'updated_at' => $date,
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
