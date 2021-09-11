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

        foreach (range(1,100) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $type = $faker->numberBetween(0, 5);
            $custom_type = NULL;
            if ($type === 0) {
                $type = NULL;
                $custom_type = $faker->realText($maxNbChars = 10, $indexSize = 1);
            }
            $status =  $faker->numberBetween(1, 3);
            $admin_message = NULL;
            if ($status == 2 && $status == 3) {
                $admin_message = $faker->realText($maxNbChars = 500, $indexSize = 3);
            }

            $picture_availability =  $faker->numberBetween(0, 1);
            $picture_name = NULL;
            $file_path = NULL;

            if ($picture_availability == 1) {
                $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppReports', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\reports', false);
                $file_path = 'storage/documents/'.$picture_name;
            }


            DB::table('complaint_types')->insert([
                'user_id' => $faker->numberBetween(1, 100),
                'complaint_type_id' => $type,
                'custom_type' => $custom_type,
                'location_address' => $faker->streetAddress(),
                'landmark' => $faker->streetName(),
                'description' => $faker->realText($maxNbChars = 500, $indexSize = 3),
                'is_anonymous' => $faker->numberBetween(0, 1),
                'admin_message' => $admin_message,
                'status' => $status,
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
