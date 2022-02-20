<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
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
        $dateTime = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

        //id 4-12
        DB::table('types')->insert([
            'name' => "Priority For Development Projects (20% Component of IRA Utilizaton)",
            'model_type' => 'Document',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);


        DB::table('types')->insert([
            'name' => "Annual Procurement Plan",
            'model_type' => 'Document',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "List of Notice of Awards - (1st Quarter January-March)",
            'model_type' => 'Document',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "List of Notice of Awards - (2nd Quarter April-June)",
            'model_type' => 'Document',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "List of Notice of Awards - (3rd Quarter July-September)",
            'model_type' => 'Document',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "List of Notice of Awards - (4th Quarter October-December)",
            'model_type' => 'Document',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Detailed Statements of Financial Performance",
            'model_type' => 'Document',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Annual Barangay Budget",
            'model_type' => 'Document',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

        DB::table('types')->insert([
            'name' => "Itemized Monthly Collection and Disbursement",
            'model_type' => 'Document',
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);

    }
}
