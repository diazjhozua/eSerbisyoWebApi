<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach (range(1,30) as $complaint_id) {
            $status = $faker->numberBetween(1, 4);
            $type = $faker->numberBetween(0, 5);
            $custom_type = NULL;
            if ($type === 0) {
                $type = NULL;
                $custom_type = $faker->realText($maxNbChars = 10, $indexSize = 1);
            }
            $complainantCount = $faker->numberBetween(1, 2);
            $defendantCount = $faker->numberBetween(1, 5);
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            DB::table('complaints')->insert([
                'user_id' => $faker->numberBetween(1, 19),
                'complaint_type_id' => $type,
                'custom_type' => $custom_type,
                'reason' => $faker->realText($maxNbChars = 500, $indexSize = 3),
                'action' => $faker->realText($maxNbChars = 500, $indexSize = 3),
                'status' => $status,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            foreach (range(1, $complainantCount) as $index) {
                $picture = $faker->file($sourceDir = 'C:\Project Assets\AppSignatures', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\signatures', false);
                $file_path = 'public/signatures/'.$picture;
                DB::table('complainants')->insert([
                    'complaint_id' => $complaint_id,
                    'name' => $faker->name(),
                    'signature_picture' => $picture,
                    'file_path' => $file_path,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            foreach (range(1, $defendantCount) as $index) {
                DB::table('defendants')->insert([
                    'complaint_id' => $complaint_id,
                    'name' => $faker->name(),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

        }
    }
}
