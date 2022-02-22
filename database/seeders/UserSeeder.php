<?php

namespace Database\Seeders;

use App\Models\User;
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
        activity()->disableLogging();
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
                $file_path = 'users/'.$picture_name;
                $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                DB::table('users')->insert([
                    'first_name' =>  $faker->firstName,
                    'middle_name' => $faker->lastName,
                    'last_name' => $faker->lastName,
                    'email' => $position[$positionNum].$index.'@gmail.com',
                    'phone_no' => "09560492498",
                    'password' => Hash::make('12341234'),
                    'picture_name' => $picture_name,
                    'file_path' => $file_path,
                    'purok_id' => $faker->numberBetween(1,5),
                    'address' => $faker->address,
                    'is_verified' => true,
                    'status' => 'Enable',
                    'user_role_id' => $positionNum,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }
        }

        // For biker
        foreach (range(1,20) as $index)
        {
            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppUsers', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\users', false);
            $file_path = 'users/'.$picture_name;
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
	        DB::table('users')->insert([
                'first_name' =>  $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => strtolower($faker->firstName.$faker->lastName.$faker->buildingNumber).'@gmail.com',
                'phone_no' => "09560492498",
                'password' => Hash::make('12341234'),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'Enable',
                'user_role_id' => 8,
                'bike_type' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'bike_color' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'bike_size' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }


        foreach (range(6,19) as $index)
        {
            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppUsers', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\users', false);
            $file_path = 'users/'.$picture_name;
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
	        DB::table('users')->insert([
                'first_name' =>  $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => strtolower($faker->firstName.$faker->lastName.$faker->buildingNumber).'@gmail.com',
                'phone_no' => "09560492498",
                'password' => Hash::make('12341234'),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'Enable',
                'user_role_id' => 9,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }

        foreach (range(20,30) as $index)
        {
            $picture_name = $faker->file($sourceDir = 'C:\Project Assets\AppUsers', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\users', false);
            $file_path = 'users/'.$picture_name;
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

	        DB::table('users')->insert([
                'first_name' =>  $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => strtolower($faker->firstName.$faker->lastName.$faker->buildingNumber).'@gmail.com',
                'phone_no' => "09560492498",
                'password' => Hash::make('12341234'),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => false,
                'status' => 'Enable',
                'user_role_id' => 9,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }

        $unverifiedUsers = User::where('is_verified', 0)->get();
        foreach ($unverifiedUsers as $user) {
            $credentials_name = $faker->file($sourceDir = 'C:\Project Assets\AppID', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\credentials', false);
            $credentials_file_path = 'credentials/'.$credentials_name;
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

	        DB::table('user_verifications')->insert([
                'user_id' =>  $user->id,
                'credential_name' => $credentials_name,
                'credential_file_path' => $credentials_file_path,
                'admin_message' => '',
                'status' => 'Pending',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }

        $users = [];
        foreach (range(1,500) as $index)
        {
            $timestamp = $faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = null);
	        $users[] = [
                'first_name' =>  $faker->firstName($gender = null),
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => strtolower($faker->firstName.$faker->lastName.$faker->buildingNumber).'@gmail.com',
                'phone_no' => "09560492498",
                'password' => Hash::make('12341234'),
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => false,
                'status' => 'Enable',
                'user_role_id' => 9,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ];
        }

        $chunks = array_chunk($users, 40);
        foreach ($chunks as $chunk) {
            User::insert($chunk);
        }

        // for biker request application
        foreach (range(1,30) as $index)
        {
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $credentials_name = $faker->file($sourceDir = 'C:\Project Assets\AppBikers', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\bikers', false);
            $credentials_file_path = 'bikers/'.$credentials_name;
            DB::table('biker_requests')->insert([
                'user_id' => $faker->numberBetween(20, 500),
                'phone_no' => $faker->phoneNumber,
                'bike_type' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'bike_color' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'bike_size' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'reason' => $faker->realText($maxNbChars = 150, $indexSize = 2),
                'credential_name' => $credentials_name,
                'credential_file_path' => $credentials_file_path,
                'admin_message' => '',
                'status' => 'Pending',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
