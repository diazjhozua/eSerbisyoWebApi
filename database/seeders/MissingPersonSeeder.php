<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissingPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach (range(1,30) as $index) {
            DB::table('missing_persons')->insert([
                'user_id' => $faker->numberBetween(1, 19),
                'name' => $faker->name(),
                'height' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1.6, $max = 8),
                'weight' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                'age' => $faker->numberBetween(1,120),
                'eyes' => $faker->colorName(),
                'hair' => $faker->colorName(),
                'unique_sign' => $faker->sentence($nbWords = 4, $variableNbWords = true),
                'important_information' => $faker->sentence($nbWords = 10, $variableNbWords = true),
                'last_seen' => $faker->streetName(),
                'contact_information' => $faker->tollFreePhoneNumber(),
                'is_resolved' => $faker->numberBetween(0, 1),
                'is_approved' => $faker->numberBetween(0, 1),
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            ]);
        }
    }
}
