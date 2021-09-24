<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $boolean = [true, false];
        foreach (range(26, 34) as $index) {
           

            foreach (range(1,9) as $index) {
                // $projectTypeId = $faker->numberBetween(36, 43);
                // $project = new Project();
                // $project->type_id = $projectTypeId;
                $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                $pdf_name = $faker->file($sourceDir = 'C:\Project Assets\AppProjects', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\projects', false);
                $file_path = 'storage/projects/'.$pdf_name;
          
            
                DB::table('projects')->insert([
                'type_id' => $index,
                'name' => $faker->name($nbWords = 6, $variableNbWords = true),
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'cost' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 5000),
                'project_start' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'project_end' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'location' => $faker->sentence($nbSentences = 3, $variableNbSentences = true),
                'is_starting' => $boolean[array_rand($boolean)],
                'pdf_name' => $pdf_name,
                'file_path'=> $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
            }
        }
    }
}
