<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            $status = $faker->numberBetween(1, 4);
            $type = $faker->numberBetween(1, 2);
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            DB::table('lost_and_founds')->insert([
                'user_id' => $faker->numberBetween(1, 19),
                'item' => $faker->sentence($nbWords = 3, $variableNbWords = true),
                'last_seen' => $faker->streetName(),
                'description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
                'contact_information' => $faker->tollFreePhoneNumber(),
                'status' => $status,
                'report_type' => $type,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
