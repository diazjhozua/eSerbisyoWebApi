<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AndroidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        foreach (range(1,5) as $index) {
            activity()->disableLogging();
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppEmployees', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\androids', false);
            $file_path = 'androids/'.$picture_name;

            DB::table('androids')->insert([
                'version' => $faker->name,
                'description' => $faker->realText($maxNbChars = 10, $indexSize = 2),
                'file_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
                'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
            ]);
        }
    }
}
