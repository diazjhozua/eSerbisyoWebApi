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
        $faker = \Faker\Factory::create();

        $status = ['Available', 'Unavailable'];

        foreach (range(1,10) as $certReqID) {
            $requirement = Requirement::create([
                'name' => $faker->realText($maxNbChars = 150, $indexSize = 1),
            ]);
        }

        Certificate::create([
            'name' => 'Barangay Indigency',
            'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
            'status' => $status[array_rand($status)],
            'is_open_delivery' => $faker->numberBetween(0, 1),
            'delivery_fee' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
        ]);

        Certificate::create([
            'name' => 'Barangay Cedula',
            'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
            'status' => $status[array_rand($status)],
            'is_open_delivery' => $faker->numberBetween(0, 1),
            'delivery_fee' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
        ]);

        Certificate::create([
            'name' => 'Barangay Clearance',
            'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
            'status' => $status[array_rand($status)],
            'is_open_delivery' => $faker->numberBetween(0, 1),
            'delivery_fee' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
        ]);

        Certificate::create([
            'name' => 'Barangay ID',
            'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
            'status' => $status[array_rand($status)],
            'is_open_delivery' => $faker->numberBetween(0, 1),
            'delivery_fee' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
        ]);

        Certificate::create([
            'name' => 'Barangay Business Permit',
            'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
            'status' => $status[array_rand($status)],
            'is_open_delivery' => $faker->numberBetween(0, 1),
            'delivery_fee' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500),
        ]);

        foreach (range(1,5) as $certID) {
            // certificate_requirements
            $requirementsCount = $faker->numberBetween(1,4);
            foreach (range(1,$requirementsCount) as $reqCertID) {
                $certificateRequirement = CertificateRequirement::create([
                    'certificate_id' => $certID,
                    'requirement_id' => $faker->unique()->numberBetween(1, 10),
                ]);
            }
            $faker->unique($reset = true);
        }
    }
}
