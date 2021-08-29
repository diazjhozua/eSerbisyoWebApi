<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        //1
        \DB::table('user_roles')->insert([
            'role' => "Super Admin",
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        //2
        \DB::table('user_roles')->insert([
            'role' => "Information Admin",
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        //3
        \DB::table('user_roles')->insert([
            'role' => "Certification Admin",
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        //4
        \DB::table('user_roles')->insert([
            'role' => "Information Staff",
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        //5
        \DB::table('user_roles')->insert([
            'role' => "Certification Staff",
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        //6
        \DB::table('user_roles')->insert([
            'role' => "Resident",
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

    }
}
