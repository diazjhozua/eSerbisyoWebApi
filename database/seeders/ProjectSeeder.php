<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{

    public function run()
    {
        activity()->disableLogging();
        $faker = \Faker\Factory::create();

        $files = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936636/sample/ordinances/no.-15-143_zwfmkn.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936619/sample/ordinances/no.-13-009-river-rehabilitation-and-protection-council_jfrflh.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936580/sample/ordinances/no.-17-087_lrcc0k.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936558/sample/ordinances/no.-19-246_hrodtt.pdf',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936519/sample/ordinances/no.-19-251_ovbyuz.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936518/sample/ordinances/no.-19-248_ykdyyw.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936491/sample/ordinances/no.-19-244_ihlwch.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936466/sample/ordinances/blg-10-109_svwqoj.pdf',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936462/sample/ordinances/blg-09-087_nrjvsp.pdf',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936295/sample/documents/lna-2ndq-converted_v1bk6o.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936293/sample/documents/financial-performance-2020_shj9pm.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936289/sample/documents/priority-development-projects-2021_zfibqv.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936265/sample/documents/imcd-june-2021_swwhaw.pdf',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936269/sample/documents/annunal-procurement-plan-2021_i0nyt1.pdf',

        ];

        foreach(range(1,100) as $index) {
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $file_path = $files[array_rand($files)];

            $file_name = 'barangay/'.uniqid().'-'.time();

            $startDate = $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null);
            $endDate = $faker->dateTimeBetween($startDate, $endDate = '+2 years');

            DB::table('projects')->insert([
                'type_id' => rand(21,25),
                'name' => $faker->realText($maxNbChars = 20, $indexSize = 1),
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'cost' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 5000),
                'project_start' => $startDate->format('y/m/d'),
                'project_end' => $endDate->format('y/m/d'),
                'location' => $faker->address(),
                'pdf_name' => $file_name,
                'file_path'=> $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }
    }
}
