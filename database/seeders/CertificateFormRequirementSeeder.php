<?php

namespace Database\Seeders;

use App\Models\CertificateForm;
use App\Models\CertificateFormRequirement;
use App\Models\CertificateRequirement;

use Illuminate\Database\Seeder;

class CertificateFormRequirementSeeder extends Seeder
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

        $certificateForms = CertificateForm::all();
        foreach ($certificateForms as $certificateForm) {

            $CertificateRequirements = CertificateRequirement::where('certificate_id', $certificateForm->certificate_id)->get();

            foreach ($CertificateRequirements as $requirement) {
                $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                $file_name = $faker->file($sourceDir = 'C:\Project Assets\AppSignatures', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\requirements', false);
                $file_path = 'public/requirements/'.$file_name;

                CertificateFormRequirement::create([
                    'certificate_form_id' => $certificateForm->id,
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
