<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LostAndFoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach (range(1,20) as $index) {
            \DB::table('lost_and_founds')->insert([
                'user_id' => $faker->numberBetween(1, 19),
                'item' => $faker->sentence($nbWords = 3, $variableNbWords = true),
                'last_seen' => $faker->streetName(),
                'description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
                'contact_information' => $faker->tollFreePhoneNumber(),
                'is_resolved' => $faker->numberBetween(0, 1),
                'is_approved' => $faker->numberBetween(0, 1),
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            ]);
        }
    }
}
