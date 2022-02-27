<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
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

        $files = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936295/sample/documents/lna-2ndq-converted_v1bk6o.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936293/sample/documents/financial-performance-2020_shj9pm.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936289/sample/documents/priority-development-projects-2021_zfibqv.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936265/sample/documents/imcd-june-2021_swwhaw.pdf',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936269/sample/documents/annunal-procurement-plan-2021_i0nyt1.pdf',
        ];

        foreach (range(4,12) as $id) {

            $year = 2017;
            foreach (range(1,5) as $index) {

                $file_path = $files[array_rand($files)];
                $file_name = 'barangay/'.uniqid().'-'.time();
                $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                DB::table('documents')->insert([
                    'type_id' => $id,
                    'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                    'year' => $year,
                    'pdf_name'=> $file_name,
                    'file_path'=> $file_path,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $year ++;
            }

        }
    }
}
