<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        // For super admin
        foreach (range(1,2) as $index)
        {
            $randomNum = $faker->unique()->numberBetween(0, 18);
	        \DB::table('users')->insert([
                'first_name' =>  $firstName[$randomNum],
                'middle_name' => $faker->lastName,
                'last_name' => $lastName[$randomNum],
                'email' => strtolower($lastName[$randomNum]).'@gmail.com',
                'password' => Hash::make('12341234'),
                'picture' => $lastName[$randomNum].'-photo-1001.jpg',
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'enable',
                'user_role_id' => 1,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
	        ]);
        }

        // For information admin
        foreach (range(3,4) as $index)
        {
            $randomNum = $faker->unique()->numberBetween(0, 18);
	        \DB::table('users')->insert([
                'first_name' =>  $firstName[$randomNum],
                'middle_name' => $faker->lastName,
                'last_name' => $lastName[$randomNum],
                'email' => strtolower($lastName[$randomNum]).'@gmail.com',
                'password' => Hash::make('12341234'),
                'picture' => $lastName[$randomNum].'-photo-1001.jpg',
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'enable',
                'user_role_id' => 2,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
	        ]);
        }

        // For certificate admin
        foreach (range(5,6) as $index)
        {
            $randomNum = $faker->unique()->numberBetween(0, 18);
	        \DB::table('users')->insert([
                'first_name' =>  $firstName[$randomNum],
                'middle_name' => $faker->lastName,
                'last_name' => $lastName[$randomNum],
                'email' => strtolower($lastName[$randomNum]).'@gmail.com',
                'password' => Hash::make('12341234'),
                'picture' => $lastName[$randomNum].'-photo-1001.jpg',
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'enable',
                'user_role_id' => 3,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
	        ]);
        }

        // For information staff
        foreach (range(7,9) as $index)
        {
            $randomNum = $faker->unique()->numberBetween(0, 18);
	        \DB::table('users')->insert([
                'first_name' =>  $firstName[$randomNum],
                'middle_name' => $faker->lastName,
                'last_name' => $lastName[$randomNum],
                'email' => strtolower($lastName[$randomNum]).'@gmail.com',
                'password' => Hash::make('12341234'),
                'picture' => $lastName[$randomNum].'-photo-1001.jpg',
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'enable',
                'user_role_id' => 4,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
	        ]);
        }

        // For certificate staff
        foreach (range(10,12) as $index)
        {
            $randomNum = $faker->unique()->numberBetween(0, 18);
	        \DB::table('users')->insert([
                'first_name' =>  $firstName[$randomNum],
                'middle_name' => $faker->lastName,
                'last_name' => $lastName[$randomNum],
                'email' => strtolower($lastName[$randomNum]).'@gmail.com',
                'password' => Hash::make('12341234'),
                'picture' => $lastName[$randomNum].'-photo-1001.jpg',
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'enable',
                'user_role_id' => 5,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
	        ]);
        }

        // For resident
        foreach (range(13,19) as $index)
        {
            $randomNum = $faker->unique()->numberBetween(0, 18);
	        \DB::table('users')->insert([
                'first_name' =>  $firstName[$randomNum],
                'middle_name' => $faker->lastName,
                'last_name' => $lastName[$randomNum],
                'email' => strtolower($lastName[$randomNum]).'@gmail.com',
                'password' => Hash::make('12341234'),
                'picture' => $lastName[$randomNum].'-photo-1001.jpg',
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'enable',
                'user_role_id' => 5,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
	        ]);
        }
    }
}
