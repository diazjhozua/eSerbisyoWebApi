<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $status = ['Pending', 'Ignored', 'Invalid', 'Noted'];
        $urgency_classification = ['Nonurgent', 'Urgent'];

        foreach (range(1,100) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $type = $faker->numberBetween(26, 30);
            $custom_type = NULL;
            $isNull = $faker->numberBetween(0,1);

            if ($isNull === 0) {
                $type = NULL;
                $custom_type = $faker->realText($maxNbChars = 10, $indexSize = 1);
            }

            $picture_availability =  $faker->numberBetween(0, 1);
            $picture_name = NULL;
            $file_path = NULL;

            if ($picture_availability == 1) {
                $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppReports', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\reports', false);
                $file_path = 'storage/reports/'.$picture_name;
            }

            DB::table('reports')->insert([
                'user_id' => $faker->numberBetween(1, 37),
                'type_id' => $type,
                'custom_type' => $custom_type,
                'location_address' => $faker->streetAddress(),
                'landmark' => $faker->streetName(),
                'description' => $faker->realText($maxNbChars = 500, $indexSize = 3),
                'is_anonymous' => $faker->numberBetween(0, 1),
                'urgency_classification' => $urgency_classification[array_rand($urgency_classification)],
                'status' => $status[array_rand($status)],
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
