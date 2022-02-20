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

        foreach (range(4,12) as $id) {

            $year = 2017;
            foreach (range(1,5) as $index) {
                $pdf_name = $faker->file($sourceDir = 'C:\Project Assets\AppDocuments', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\documents', false);
                $file_path = 'documents/'.$pdf_name;
                $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                DB::table('documents')->insert([
                    'type_id' => $id,
                    'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                    'year' => $year,
                    'pdf_name'=> $pdf_name,
                    'file_path'=> $file_path,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $year ++;
            }

        }

        // DB::table('documents')->insert([
        //     'document_type_id' => 1,
        //     'year' => 2021,
        //     'pdf_name'=> 'priority-development-projects-2021.pdf',
        //     'file_path'=> 'documents/priority-development-projects-2021.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('documents')->insert([
        //     'document_type_id' => 2,
        //     'year' => 2021,
        //     'pdf_name'=> 'annunal-procurement-plan-2021.pdf',
        //     'file_path'=> 'documents/annunal-procurement-plan-2021.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('documents')->insert([
        //     'document_type_id' => 4,
        //     'year' => 2021,
        //     'pdf_name'=> 'lna-2ndq-converted.pdf',
        //     'file_path'=> 'documents/lna-2ndq-converted.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('documents')->insert([
        //     'document_type_id' => 7,
        //     'year' => 2020,
        //     'pdf_name'=> 'financial-performance-2020.pdf',
        //     'file_path'=> 'documents/financial-performance-2020.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('documents')->insert([
        //     'document_type_id' => 8,
        //     'year' => 2021,
        //     'pdf_name'=> 'barangay-cupang-budget-2021.pdf',
        //     'file_path'=> 'documents/barangay-cupang-budget-2021.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('documents')->insert([
        //     'document_type_id' => 9,
        //     'description' => 'For the month of June',
        //     'year' => 2021,
        //     'pdf_name'=> 'imcd-june-2021.pdf',
        //     'file_path'=> 'documents/imcd-june-2021.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);
    }
}
