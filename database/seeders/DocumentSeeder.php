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
        $faker = \Faker\Factory::create();

        DB::table('documents')->insert([
            'document_type_id' => 1,
            'year' => 2021,
            'pdf_name'=> 'priority-development-projects-2021.pdf',
            'file_path'=> 'storage/documents/priority-development-projects-2021.pdf',
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        DB::table('documents')->insert([
            'document_type_id' => 2,
            'year' => 2021,
            'pdf_name'=> 'annunal-procurement-plan-2021.pdf',
            'file_path'=> 'storage/documents/annunal-procurement-plan-2021.pdf',
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        DB::table('documents')->insert([
            'document_type_id' => 4,
            'year' => 2021,
            'pdf_name'=> 'lna-2ndq-converted.pdf',
            'file_path'=> 'storage/documents/lna-2ndq-converted.pdf',
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        DB::table('documents')->insert([
            'document_type_id' => 7,
            'year' => 2020,
            'pdf_name'=> 'financial-performance-2020.pdf',
            'file_path'=> 'storage/documents/financial-performance-2020.pdf',
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        DB::table('documents')->insert([
            'document_type_id' => 8,
            'year' => 2021,
            'pdf_name'=> 'barangay-cupang-budget-2021.pdf',
            'file_path'=> 'storage/documents/barangay-cupang-budget-2021.pdf',
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);

        DB::table('documents')->insert([
            'document_type_id' => 9,
            'description' => 'For the month of June',
            'year' => 2021,
            'pdf_name'=> 'imcd-june-2021.pdf',
            'file_path'=> 'storage/documents/imcd-june-2021.pdf',
            'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        ]);
    }
}
