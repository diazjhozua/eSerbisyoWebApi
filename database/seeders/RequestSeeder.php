<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();
        $certificates = collect(Certificate::all()->modelKeys());
        $civil_status = ['Single', 'Married', 'Divorced', 'Widowed'];

        for ($i = 0; $i < 50; $i++) {
            $certificateID = $certificates->random();
            $picture = $faker->file($sourceDir = 'C:\Project Assets\AppSignatures', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\signatures', false);
            $file_path = 'public/signatures/'.$picture;
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

            switch ($certificateID) {
                case 1: //brgyIndigency
                    Request::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'name' => $faker->name() ,
                        'address' => $faker->streetAddress(),
                        'purpose' => $faker->realText(150, 2),
                        'signature_picture' => $picture,
                        'file_path' => $file_path,
                        'status' => 'Pending',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                    break;
                case 2: //brgyCedula
                    Request::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'name' => $faker->name() ,
                        'address' => $faker->streetAddress(),
                        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                        'birthplace' => $faker->city(),
                        'citizenship' => $faker->word(),
                        'civil_status' => $civil_status[array_rand($civil_status)],
                        'signature_picture' => $picture,
                        'file_path' => $file_path,
                        'status' => 'Pending',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    break;
                case 3: //brgyClearance
                    Request::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'name' => $faker->name() ,
                        'address' => $faker->streetAddress(),
                        'purpose' => $faker->realText(150, 2),
                        'signature_picture' => $picture,
                        'file_path' => $file_path,
                        'status' => 'Pending',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    break;
                case 4: //brgyID
                    Request::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'name' => $faker->name() ,
                        'address' => $faker->streetAddress(),
                        'contact_no' => $faker->phoneNumber(),
                        'contact_person' => $faker->name(),
                        'contact_person_no' => '012312',
                        'contact_person_relation' => $faker->word(),
                        'signature_picture' => $picture,
                        'file_path' => $file_path,
                        'status' => 'Pending',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    break;
                case 5: //businessPermit
                    Request::create([
                        'user_id' => rand(1,37),
                        'certificate_id' => $certificateID,
                        'name' => $faker->name(),
                        'address' => $faker->streetAddress(),
                        'business_name' => $faker->realText(20, 1),
                        'signature_picture' => $picture,
                        'file_path' => $file_path,
                        'status' => 'Pending',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    break;
            }
        }
    }
}
