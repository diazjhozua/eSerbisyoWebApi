<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //loop all the user and random the number of order they commit
        $faker = \Faker\Factory::create();

        $civil_status = ['Single', 'Married', 'Divorced', 'Widowed'];
        $users = collect(User::all()->modelKeys());

        foreach ($users as $user) {
            $userOrderCount = rand(1, 2); //random number of orders
            $orderDate = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

            for ($i = 0; $i < $userOrderCount; $i++) {
                // request count
                $userRequestCount = rand(1,2);
                $requests = [];

                for ($j = 0; $j < $userRequestCount; $j++) {
                    $certificateID = $faker->unique()->numberBetween(1, 5);
                    $picture = $faker->file($sourceDir = 'C:\Project Assets\AppSignatures', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\signatures', false);
                    $file_path = 'public/signatures/'.$picture;
                    $requestDate = $faker->dateTimeBetween($startDate = '-1 years', $orderDate, $timezone = null);

                    switch ($certificateID) {
                        case 1: //brgyIndigency
                            $requests[] = [
                                'user_id' => $user,
                                'certificate_id' => $certificateID,
                                'name' => $faker->name($gender = null|'male'|'female'),
                                'address' => $faker->streetAddress(),
                                'purpose' => $faker->realText(150, 2),
                                'signature_picture' => $picture,
                                'file_path' => $file_path,
                                'created_at' => $requestDate,
                                'updated_at' => $requestDate,
                            ];

                            break;
                        case 2: //brgyCedula
                            $requests[] = [
                                'user_id' => $user,
                                'certificate_id' => $certificateID,
                                'name' => $faker->name($gender = null|'male'|'female'),
                                'address' => $faker->streetAddress(),
                                'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                                'birthplace' => $faker->city(),
                                'citizenship' => $faker->word(),
                                'civil_status' => $civil_status[array_rand($civil_status)],
                                'signature_picture' => $picture,
                                'file_path' => $file_path,
                                'created_at' => $requestDate,
                                'updated_at' => $requestDate,
                            ];

                            break;
                        case 3: //brgyClearance
                            $requests[] = [
                                'user_id' => $user,
                                'certificate_id' => $certificateID,
                                'name' => $faker->name($gender = null|'male'|'female'),
                                'address' => $faker->streetAddress(),
                                'purpose' => $faker->realText(150, 2),
                                'signature_picture' => $picture,
                                'file_path' => $file_path,
                                'created_at' => $requestDate,
                                'updated_at' => $requestDate,
                            ];

                            break;
                        case 4: //brgyID
                            $requests[] = [
                                'user_id' => $user,
                                'certificate_id' => $certificateID,
                                'name' => $faker->name($gender = null|'male'|'female'),
                                'address' => $faker->streetAddress(),
                                'contact_no' => $faker->phoneNumber(),
                                'contact_person' => $faker->name($gender = null|'male'|'female'),
                                'contact_person_no' => $faker->name($gender = null|'male'|'female'),
                                'contact_person_relation' => $faker->word(),
                                'signature_picture' => $picture,
                                'file_path' => $file_path,
                                'created_at' => $requestDate,
                                'updated_at' => $requestDate,
                            ];
                            break;
                        case 5: //businessPermit
                            $requests[] = [
                                'user_id' => $user,
                                'certificate_id' => $certificateID,
                                'name' => $faker->name($gender = null|'male'|'female'),
                                'address' => $faker->streetAddress(),
                                'business_name' => $faker->realText(20, 1),
                                'signature_picture' => $picture,
                                'file_path' => $file_path,
                                'created_at' => $requestDate,
                                'updated_at' => $requestDate,
                            ];
                            break;

                    }
                    //end of switch case
                }

                $faker->unique($reset = true);



                // $order = Order::create([
                //     'ordered_by' => $user,
                //     'delivered_by' => $users->random(),
                //     'total_price' =>
                // ]);

            }
        }
    }
}
