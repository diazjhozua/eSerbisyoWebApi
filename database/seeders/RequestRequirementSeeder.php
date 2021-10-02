<?php

namespace Database\Seeders;

use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use App\Models\CertificateRequirement;
use App\Models\Request;
use App\Models\RequestRequirement;
use Illuminate\Database\Seeder;

class RequestRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $requests = Request::all();
        foreach ($requests as $request) {

            $CertificateRequirements = CertificateRequirement::where('certificate_id', $request->certificate_id)->get();

            foreach ($CertificateRequirements as $requirement) {
                $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                $file_name = $faker->file($sourceDir = 'C:\Project Assets\AppSignatures', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\requirements', false);
                $file_path = 'public/requirements/'.$file_name;

                RequestRequirement::create([
                    'request_id' => $request->id,
                    'requirement_id' => $requirement->requirement_id,
                    'file_name' => $file_name,
                    'file_path' => $file_path,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
