<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PurokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        activity()->disableLogging();

        \DB::table('puroks')->insert([
            'purok' => "Purok 1",
            'created_at' => $this->faker->dateTime,
        ]);

        \DB::table('puroks')->insert([
            'purok' => "Purok 2",
            'created_at' => $this->faker->dateTime,
        ]);

        \DB::table('puroks')->insert([
            'purok' => "Purok 3",
            'created_at' => $this->faker->dateTime,
        ]);

        \DB::table('puroks')->insert([
            'purok' => "Purok 4",
            'created_at' => $this->faker->dateTime,
        ]);

        \DB::table('puroks')->insert([
            'purok' => "Purok 5",
            'created_at' => $this->faker->dateTime,
        ]);
    }
}
