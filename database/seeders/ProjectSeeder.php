<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{

    public function run()
    {
        $faker = \Faker\Factory::create();
        $boolean = [true, false];
        foreach(range(1,100) as $index) {
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $pdf_name = $faker->file($sourceDir = 'C:\Project Assets\AppProjects', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\projects', false);
            $file_path = 'storage/projects/'.$pdf_name;

            $startDate = $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null);
            $endDate = $faker->dateTimeBetween($startDate, $endDate = '+2 years');

            DB::table('projects')->insert([
                'type_id' => rand(21,25),
                'name' => $faker->realText($maxNbChars = 20, $indexSize = 1),
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'cost' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 5000),
                'project_start' => $startDate->format('y/m/d'),
                'project_end' => $endDate->format('y/m/d'),
                'location' => $faker->address(),
                // 'is_starting' => $boolean[array_rand($boolean)],
                'pdf_name' => $pdf_name,
                'file_path'=> $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }
    }
}
