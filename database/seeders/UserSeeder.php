<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $firstName = array('Jayson', 'Jose', 'Cassidy', 'Mark Joseph', 'Marlou',
        'Jhay-R', 'Jhozua', 'Lou', 'Mark Joseph', 'Eileen',
        'Ezekiel', 'Princess', 'Zam', 'Angelo', 'Albert',
        'Kier', 'Alexander', 'Clariz', 'Carl');

        $lastName = array('Aguilando', 'Arboleda', 'Carbajosa', 'Casas', 'Cerilla',
        'Cortez', 'Diaz', 'Gongob', 'Guerrero', 'Huang',
        'Lacbayen', 'Leano', 'Magallanes', 'Mijares', 'Mituda',
        'Sales', 'Udag', 'Velez', 'Villanueva');

        $position = [
            1 => 'superadmin',
            2 => 'informationadmin',
            3 => 'certificationadmin',
            4 => 'taskforceadmin',
            5 => 'informationstaff',
            6 => 'certificationstaff',
            7 => 'taskforcestaff',
            8 => 'biker',
            9 => 'resident',
        ];

        foreach (range(1,9) as $positionNum) {
            foreach(range(1,2) as $index) {
                $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppUsers', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\users', false);
                $file_path = 'storage/users/'.$picture_name;
                $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                DB::table('users')->insert([
                    'first_name' =>  $faker->firstName,
                    'middle_name' => $faker->lastName,
                    'last_name' => $faker->lastName,
                    'email' => $position[$positionNum].$index.'gmail.com',
                    'password' => Hash::make('12341234'),
                    'picture_name' => $picture_name,
                    'file_path' => $file_path,
                    'purok_id' => $faker->numberBetween(1,5),
                    'address' => $faker->address,
                    'is_verified' => true,
                    'status' => 'enable',
                    'user_role_id' => $positionNum,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }
        }

        // For biker
        foreach (range(1,5) as $index)
        {
            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppUsers', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\users', false);
            $file_path = 'storage/users/'.$picture_name;
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $randomNum = $faker->unique()->numberBetween(0, 4);
	        DB::table('users')->insert([
                'first_name' =>  $firstName[$randomNum],
                'middle_name' => $faker->lastName,
                'last_name' => $lastName[$randomNum],
                'email' => strtolower($lastName[$randomNum]).'@gmail.com',
                'password' => Hash::make('12341234'),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'enable',
                'user_role_id' => 8,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }

        foreach (range(6,19) as $index)
        {
            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppUsers', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\users', false);
            $file_path = 'storage/users/'.$picture_name;
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $randomNum = $faker->unique()->numberBetween(5, 18);
	        DB::table('users')->insert([
                'first_name' =>  $firstName[$randomNum],
                'middle_name' => $faker->lastName,
                'last_name' => $lastName[$randomNum],
                'email' => strtolower($lastName[$randomNum]).'@gmail.com',
                'password' => Hash::make('12341234'),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'enable',
                'user_role_id' => 9,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }
    }
}
