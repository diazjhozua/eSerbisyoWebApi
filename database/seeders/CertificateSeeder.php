<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateRequirement;
use App\Models\Requirement;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
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

        $status = ['Available', 'Unavailable'];

        $requirement = Requirement::create([
            'name' => 'Valid ID',
        ]);

        $requirement = Requirement::create([
            'name' =>  'Barangay Cedula',
        ]);

        $requirement = Requirement::create([
            'name' => 'Barangay Clearance',
        ]);

        $requirement = Requirement::create([
            'name' => 'Birth Certificate',
        ]);

        $requirement = Requirement::create([
            'name' => 'NBI Clearance',
        ]);

        $requirement = Requirement::create([
            'name' => 'Municipal Business Permit',
        ]);

        Certificate::create([
            'name' => 'Barangay Indigency',
            'price' => 100,
            'status' => "Available",
            'is_open_delivery' => $faker->numberBetween(0, 1),
        ]);

        Certificate::create([
            'name' => 'Barangay Cedula',
            'price' => 150,
            'status' => "Available",
            'is_open_delivery' => $faker->numberBetween(0, 1),
        ]);

        Certificate::create([
            'name' => 'Barangay Clearance',
            'price' => 100,
            'status' => "Available",
            'is_open_delivery' => $faker->numberBetween(0, 1),
        ]);

        Certificate::create([
            'name' => 'Barangay ID',
            'price' => 100,
            'status' => "Available",
            'is_open_delivery' => $faker->numberBetween(0, 1),
        ]);

        Certificate::create([
            'name' => 'Barangay Business Permit',
            'price' => 100,
            'status' => "Available",
            'is_open_delivery' => $faker->numberBetween(0, 1),
        ]);

        foreach (range(1,5) as $certID) {
            // certificate_requirements
            $requirementsCount = $faker->numberBetween(1,2);
            foreach (range(1,$requirementsCount) as $reqCertID) {
                $certificateRequirement = CertificateRequirement::insert([
                    'certificate_id' => $certID,
                    'requirement_id' => $faker->unique()->numberBetween(1, 6),
                ]);
            }
            $faker->unique($reset = true);
        }
    }
}
