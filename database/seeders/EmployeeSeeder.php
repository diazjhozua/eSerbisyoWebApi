<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach(range(1,2) as $term) {
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppEmployees', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\employees', false);
            $file_path = 'storage/employees/'.$picture_name;

            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => $term,
                'position_id' => 1,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppEmployees', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\employees', false);
            $file_path = 'storage/employees/'.$picture_name;

            foreach (range(1,7) as $index) {
                DB::table('employees')->insert([
                    'name' => $faker->name,
                    'term_id' => $term,
                    'position_id' => 2,
                    'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                    'picture_name' => $picture_name,
                    'file_path' => $file_path,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }

            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppEmployees', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\employees', false);
            $file_path = 'storage/employees/'.$picture_name;

            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => $term,
                'position_id' => 3,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppEmployees', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\employees', false);
            $file_path = 'storage/employees/'.$picture_name;

            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => $term,
                'position_id' => 4,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppEmployees', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\employees', false);
            $file_path = 'storage/employees/'.$picture_name;

            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => $term,
                'position_id' => 5,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppEmployees', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\employees', false);
            $file_path = 'storage/employees/'.$picture_name;

            foreach (range(1,7) as $index) {
                DB::table('employees')->insert([
                    'name' => $faker->name,
                    'term_id' => $term,
                    'position_id' => 6,
                    'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                    'picture_name' => $picture_name,
                    'file_path' => $file_path,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                 ]);
            }
        }
    }
}
