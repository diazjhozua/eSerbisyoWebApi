<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateForm;
use App\Models\Request;
use Illuminate\Database\Seeder;
use Log;

class CertificateFormSeeder extends Seeder
{
    public function run()
    {
        activity()->disableLogging();
        $faker = \Faker\Factory::create();
        $certificates = collect(Certificate::all()->modelKeys());
        $civil_status = ['Single', 'Married', 'Divorced', 'Widowed'];
        $sex = ['Male', 'Female'];
        $cedula_type = ['Individual', 'Corporation'];

        // for ($i = 0; $i < 50; $i++) {
        //     $certificateID = $certificates->random();
        //     // $picture = $faker->file($sourceDir = 'C:\Project Assets\AppSignatures', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\signatures', false);
        //     // $file_path = 'public/signatures/'.$picture;
        //     $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

        //     switch ($certificateID) {
        //         case 1: //brgyIndigency
        //             CertificateForm::create([
        //                 'user_id' => rand(1,37),
        //                 'certificate_id' => $certificateID,
        //                 'first_name' => $faker->firstName($gender = null),
        //                 'middle_name' => $faker->lastName,
        //                 'last_name' => $faker->lastName,
        //                 'address' => $faker->streetAddress(),
        //                 'purpose' => $faker->realText(150, 2),
        //                 // 'signature_picture' => $picture,
        //                 // 'file_path' => $file_path,
        //                 'status' => 'Pending',
        //                 'created_at' => $date,
        //                 'updated_at' => $date,
        //             ]);
        //             break;
        //         case 2: //brgyCedula
        //             CertificateForm::create([
        //                 'user_id' => rand(1,37),
        //                 'certificate_id' => $certificateID,
        //                 'first_name' => $faker->firstName($gender = null),
        //                 'middle_name' => $faker->lastName,
        //                 'last_name' => $faker->lastName,
        //                 'address' => $faker->streetAddress(),
        //                 'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //                 'birthplace' => $faker->city(),
        //                 'citizenship' => $faker->word(),
        //                 'civil_status' => $civil_status[array_rand($civil_status)],
        //                 'sex' => $sex[array_rand($sex)],
        //                 'cedula_type' => $cedula_type[array_rand($cedula_type)],
        //                 'height' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1.6, $max = 8),
        //                 'weight' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
        //                 'profession' => $faker->word(),
        //                 // 'signature_picture' => $picture,
        //                 // 'file_path' => $file_path,
        //                 'status' => 'Pending',
        //                 'created_at' => $date,
        //                 'updated_at' => $date,
        //             ]);
        //             break;
        //         case 3: //brgyClearance
        //             CertificateForm::create([
        //                 'user_id' => rand(1,37),
        //                 'certificate_id' => $certificateID,
        //                 'first_name' => $faker->firstName($gender = null),
        //                 'middle_name' => $faker->lastName,
        //                 'last_name' => $faker->lastName,
        //                 'address' => $faker->streetAddress(),
        //                 'purpose' => $faker->realText(150, 2),
        //                 // 'signature_picture' => $picture,
        //                 // 'file_path' => $file_path,
        //                 'status' => 'Pending',
        //                 'created_at' => $date,
        //                 'updated_at' => $date,
        //             ]);

        //             break;
        //         case 4: //brgyID
        //             CertificateForm::create([
        //                 'user_id' => rand(1,37),
        //                 'certificate_id' => $certificateID,
        //                 'first_name' => $faker->firstName($gender = null),
        //                 'middle_name' => $faker->lastName,
        //                 'last_name' => $faker->lastName,
        //                 'address' => $faker->streetAddress(),
        //                 'contact_no' => $faker->phoneNumber(),
        //                 'contact_person' => $faker->name(),
        //                 'contact_person_no' => '012312',
        //                 'contact_person_relation' => $faker->word(),
        //                 // 'signature_picture' => $picture,
        //                 // 'file_path' => $file_path,
        //                 'status' => 'Pending',
        //                 'created_at' => $date,
        //                 'updated_at' => $date,
        //             ]);

        //             break;
        //         case 5: //businessPermit
        //             CertificateForm::create([
        //                 'user_id' => rand(1,37),
        //                 'certificate_id' => $certificateID,
        //                 'first_name' => $faker->firstName($gender = null),
        //                 'middle_name' => $faker->lastName,
        //                 'last_name' => $faker->lastName,
        //                 'address' => $faker->streetAddress(),
        //                 'business_name' => $faker->realText(20, 1),
        //                 // 'signature_picture' => $picture,
        //                 // 'file_path' => $file_path,
        //                 'status' => 'Pending',
        //                 'created_at' => $date,
        //                 'updated_at' => $date,
        //             ]);

        //             break;
        //     }
        // }

        for ($i = 0; $i < 100; $i++) {
            $certificateID = $certificates->random();
            // $picture = $faker->file($sourceDir = 'C:\Project Assets\AppSignatures', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\signatures', false);
            // $file_path = 'signatures/'.$picture;
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

            switch ($certificateID) {
                case 1: //brgyIndigency
                    Log::debug('Indigency');
                    CertificateForm::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'price_filled' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                        'first_name' => $faker->firstName($gender = null),
                        'middle_name' => $faker->lastName,
                        'last_name' => $faker->lastName,
                        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                        'citizenship' => $faker->word(),
                        'civil_status' => $civil_status[array_rand($civil_status)],
                        'address' => $faker->streetAddress(),
                        'purpose' => $faker->realText(40, 1),
                        // 'signature_picture' => $picture,
                        // 'file_path' => $file_path,
                        'status' => 'Approved',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                    break;
                case 2: //brgyCedula
                    Log::debug('Cedula');
                    CertificateForm::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'price_filled' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                        'first_name' => $faker->firstName($gender = null),
                        'middle_name' => $faker->lastName,
                        'last_name' => $faker->lastName,
                        'citizenship' => $faker->word(),
                        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                        'address' => $faker->streetAddress(),
                        'birthplace' => $faker->city(),
                        'tin_no' => 123456421,
                        'icr_no' => 123456,
                        'civil_status' => $civil_status[array_rand($civil_status)],
                        'sex' => $sex[array_rand($sex)],
                        'cedula_type' => $cedula_type[array_rand($cedula_type)],
                        'height' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1.6, $max = 8),
                        'weight' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                        'profession' => $faker->word(),
                        'basic_tax' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                        // 'signature_picture' => $picture,
                        // 'file_path' => $file_path,
                        'status' => 'Approved',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    break;
                case 3: //brgyClearance
                    Log::debug('Clearance');
                    CertificateForm::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'price_filled' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                        'first_name' => $faker->firstName($gender = null),
                        'middle_name' => $faker->lastName,
                        'last_name' => $faker->lastName,
                        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                        'citizenship' => $faker->word(),
                        'civil_status' => $civil_status[array_rand($civil_status)],
                        'address' => $faker->streetAddress(),
                        'purpose' => $faker->realText(40, 1),
                        // 'signature_picture' => $picture,
                        // 'file_path' => $file_path,
                        'status' => 'Approved',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                    break;
                case 4: //brgyID
                    Log::debug('ID');
                    CertificateForm::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'price_filled' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                        'first_name' => $faker->firstName($gender = null),
                        'middle_name' => $faker->lastName,
                        'last_name' => $faker->lastName,
                        'address' => $faker->streetAddress(),
                        'contact_no' => $faker->phoneNumber(),
                        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                        'birthplace' => $faker->city(),
                        'contact_person' => $faker->name(),
                        'contact_person_no' => $faker->phoneNumber(),
                        'contact_person_relation' => $faker->word(),
                        // 'signature_picture' => $picture,
                        // 'file_path' => $file_path,
                        'status' => 'Approved',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                    break;
                case 5: //businessPermit
                    Log::debug('Business Permit');
                    CertificateForm::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'price_filled' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                        'first_name' => $faker->firstName($gender = null),
                        'middle_name' => $faker->lastName,
                        'last_name' => $faker->lastName,
                        'address' => $faker->streetAddress(),
                        'business_name' => $faker->realText(20, 1),
                        // 'signature_picture' => $picture,
                        // 'file_path' => $file_path,
                        'status' => 'Approved',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                    break;
            }
        }
    }
}
