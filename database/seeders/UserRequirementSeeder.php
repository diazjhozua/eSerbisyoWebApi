<?php

namespace Database\Seeders;

use App\Models\Requirement;
use App\Models\User;
use App\Models\UserRequirement;
use Illuminate\Database\Seeder;
use Log;

class UserRequirementSeeder extends Seeder
{

    public function run()
    {
        $users = User::where('is_verified', '=', 1)->get();
        $faker = \Faker\Factory::create();

        $requirements = Requirement::get();
        foreach ($users as $user) {
            foreach ($requirements as $requirement) {
                $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                $file_name = $faker->file($sourceDir = 'C:\Project Assets\AppRequirements', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\requirements', false);
                $file_path = 'requirements/'.$file_name;

                UserRequirement::create([
                    'user_id' => $user->id,
                    'requirement_id' => $requirement->id,
                    'file_name' => $file_name,
                    'file_path' => $file_path,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

            }
        }


    }
}
