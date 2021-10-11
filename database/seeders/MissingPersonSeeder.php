<?php

namespace Database\Seeders;

use App\Models\MissingPerson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissingPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $height_scale = ['feet(ft)', 'centimeter(cm)'];
        $weight_scale = ['kilogram(kg)', 'pound(lbs)'];

        $status = ['Pending', 'Denied', 'Approved', 'Resolved'];
        $report_type = ['Missing', 'Found'];

        foreach (range(1,30) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppLostAndFounds', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\missing-pictures', false);
            $file_path = 'storage/missing-pictures/'.$picture_name;

            $missingPerson = MissingPerson::create([
                'user_id' => $faker->numberBetween(1, 19),
                'name' => $faker->name(),
                'height' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1.6, $max = 8),
                'height_unit' => $height_scale[array_rand($height_scale)],
                'weight' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                'weight_unit' => $weight_scale[array_rand($weight_scale)],
                'age' => $faker->numberBetween(1,120),
                'eyes' => $faker->colorName(),
                'hair' => $faker->colorName(),
                'unique_sign' => $faker->sentence($nbWords = 4, $variableNbWords = true),
                'important_information' => $faker->sentence($nbWords = 10, $variableNbWords = true),
                'last_seen' => $faker->streetName(),
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
                $missingPerson->comments()->create([
                    'user_id' => $faker->numberBetween(1,37),
                    'body' => $faker->realText($maxNbChars = 100, $indexSize = 3)
                ]);
            }

        }
    }
}
