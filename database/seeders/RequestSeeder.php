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
        $requests = [];
        $certificates = collect(Certificate::all()->modelKeys());
        $users = collect(User::all()->modelKeys());
        $civil_status = ['Single', 'Married', 'Divorced', 'Widowed'];


        for ($i = 0; $i < 200; $i++) {
            $certificateID = $certificates->random();
            $picture = $faker->file($sourceDir = 'C:\Project Assets\AppSignatures', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\signatures', false);
            $file_path = 'public/signatures/'.$picture;
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

            switch ($certificateID) {

                case 1: //brgyIndigency
                    $requests[] = [
                        'user_id' => $users->random(),
                        'certificate_id' => $certificateID,
                        'name' => $faker->name($gender = null|'male'|'female'),
                        'address' => $faker->streetAddress(),
                        'purpose' => $faker->realText(150, 2),
                        'signature_picture' => $picture,
                        'file_path' => $file_path,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];

                    break;
                case 2: //brgyCedula
                    $requests[] = [
                        'user_id' => $users->random(),
                        'certificate_id' => $certificateID,
                        'name' => $faker->name($gender = null|'male'|'female'),
                        'address' => $faker->streetAddress(),
                        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                        'birthplace' => $faker->date(''),
                        'citizenship' => $faker->word(),
                        'civil_status' => $civil_status[array_rand($civil_status)],
                        'signature_picture' => $picture,
                        'file_path' => $file_path,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];

                    break;
                case 3: //brgyClearance
                    $requests[] = [
                        'user_id' => $users->random(),
                        'certificate_id' => $certificateID,
                        'name' => $faker->name($gender = null|'male'|'female'),
                        'address' => $faker->streetAddress(),
                        'purpose' => $faker->realText(150, 2),
                        'signature_picture' => $picture,
                        'file_path' => $file_path,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];

                    break;
                case 4: //brgyID
                    $requests[] = [
                        'user_id' => $users->random(),
                        'certificate_id' => $certificateID,
                        'name' => $faker->name($gender = null|'male'|'female'),
                        'address' => $faker->streetAddress(),
                        'purpose' => $faker->realText(150, 2),
                        'signature_picture' => $picture,
                        'file_path' => $file_path,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];

                    break;
                case 5:
                    echo "Your favorite color is green!";
                    break;
              }
        }


        // $table->id();
        // $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        // $table->foreignId('certificate_id')->constrained('certificates')->onDelete('cascade');
        // $table->string('name');
        // $table->string('address');
        // $table->date('birthday')->nullable();
        // $table->string('birthplace')->nullable();
        // $table->string('contact_no')->nullable();
        // $table->string('contact_person')->nullable();
        // $table->string('contact_person_no')->nullable();
        // $table->string('contact_person_relation')->nullable();
        // $table->string('citizenship')->nullable();
        // $table->string('purpose')->nullable();
        // $table->date('date_requested')->nullable();
        // $table->date('date_released')->nullable();
        // $table->date('date_expiry')->nullable();
        // $table->string('precint_no')->nullable();
        // $table->enum('civil_status', ['Single', 'Married', 'Divorced', 'Widowed'])->default('Single');
        // $table->string('received_by')->nullable();
        // $table->string('signature_picture');
        // $table->string('file_path');
        // $table->enum('status', ['Pending', 'Denied', 'Approved'])->default('Pending');

        // foreach(range(1,40) as $index) {
        //     $certificateID = $faker->numberBetween(1,10);
        //     $request = Request::create([
        //         'user_id' => $faker->numberBetween(1,37),
        //         'certificate' => $certificateID,
        //         'name' =>
        //     ]);
        // }
    }
}
