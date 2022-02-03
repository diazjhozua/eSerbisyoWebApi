<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateForm;
use App\Models\CertificateFormOrder;
use App\Models\CertificateOrder;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Models\Request;
use App\Models\RequestRequirement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Log;

class OrderSeeder extends Seeder
{

    public function run()
    {
        $faker = \Faker\Factory::create();

        $users = User::where('is_verified', '=', 1)->get();
        $pick_up_type = ['Pickup', 'Delivery'];
        $application_status = ['Approved', 'Denied'];

        foreach ($users as $user) {

            // random number of orders
            for($i = 0; $i <= $faker->numberBetween(1, 3); $i++) {
                $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                $application = $application_status[array_rand($application_status)];
                $pickup = $pick_up_type[array_rand($pick_up_type)];
                $long = $faker->longitude();
                $lat = $faker->latitude();

                $fileName = null;
                $filePath = null;

                if ($pickup == "Delivery") {
                    $fileName = $faker->file($sourceDir = 'C:\Project Assets\AppMissingPersons', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\orders', false);
                    $filePath = 'orders/'.$fileName;
                }

                $admin_message = null;
                if ($application != 'Pending') {
                    $admin_message = $faker->realText($maxNbChars = 50, $indexSize = 1);
                }

                $order = Order::create([
                    'ordered_by' => $user->id,
                    'delivered_by' => $pickup === 'Delivery' ?  User::where('user_role_id', '=', 8)->get()->random()->id : NULL,
                    // 'total_price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                    'name' => $faker->name,
                    'pick_up_type' => $pickup,
                    'delivery_fee' => $pickup === 'Delivery' ? 60 : 0,
                    'pickup_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'application_status' => $application,
                    'delivery_payment_status' => $pickup === 'Delivery' ? 'Pending' : NULL,
                    'order_status' => $application === 'Approved' ? 'Received' : 'Waiting',
                    'location_address' => $faker->address(),
                    'email' => $faker->lastName.$faker->email,
                    'phone_no' => $faker->phoneNumber,
                    'user_long' => $pickup === 'Delivery' ? $long : NULL,
                    'user_lat' => $pickup === 'Delivery' ? $lat : NULL,
                    'rider_long' => $pickup === 'Delivery' ? $long : NULL,
                    'rider_lat' => $pickup === 'Delivery' ? $lat : NULL,
                    'file_name' => $pickup === 'Delivery' ? $fileName : NULL,
                    'file_path' => $pickup === 'Delivery' ? $filePath : NULL,
                    'admin_message' => $admin_message,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                $totalPrice = 0;

                $fakur = \Faker\Factory::create();

                for ($i = 0; $i <= $faker->numberBetween(1, 3); $i++) {
                    $civil_status = ['Single', 'Married', 'Divorced', 'Widowed'];
                    $sex = ['Male', 'Female'];
                    $cedula_type = ['Individual', 'Corporation'];
                    $certificateID = $fakur->unique()->numberBetween(1,5);

                    $certificate = Certificate::findOrFail($certificateID);
                    $totalPrice = $totalPrice + $certificate->price;

                    switch ($certificateID) {
                        case 1: //brgyIndigency
                            Log::debug('Indigency');
                            $certificateForm = CertificateForm::create([
                                'user_id' => $user->id,
                                'certificate_id' => $certificateID,
                                'price_filled' => $certificate->price,
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
                            $basicTax = $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34);
                            $cedulaPrice = $certificate->price + $basicTax;
                            $certificateForm = CertificateForm::create([
                                'user_id' => $user->id,
                                'certificate_id' => $certificateID,
                                'price_filled' => $cedulaPrice,
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
                                'basic_tax' => $basicTax,
                                // 'signature_picture' => $picture,
                                // 'file_path' => $file_path,
                                'status' => 'Approved',
                                'created_at' => $date,
                                'updated_at' => $date,
                            ]);

                            break;
                        case 3: //brgyClearance
                            Log::debug('Clearance');
                            $certificateForm = CertificateForm::create([
                                'user_id' => $user->id,
                                'certificate_id' => $certificateID,
                                'price_filled' => $certificate->price,
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
                            $certificateForm = CertificateForm::create([
                                'user_id' => $user->id,
                                'certificate_id' => $certificateID,
                                'price_filled' => $certificate->price,
                                'first_name' => $faker->firstName($gender = null),
                                'middle_name' => $faker->lastName,
                                'last_name' => $faker->lastName,
                                'address' => $faker->streetAddress(),
                                'contact_no' => '09196988952',
                                'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                                'birthplace' => $faker->city(),
                                'contact_person' => $faker->name(),
                                'contact_person_no' => '09196988952',
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
                            $certificateForm = CertificateForm::create([
                                'user_id' => $user->id,
                                'certificate_id' => $certificateID,
                                'price_filled' => $certificate->price,
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

                    CertificateOrder::create([
                        'order_id' => $order->id,
                        'certificate_form_id' => $certificateForm->id,
                    ]);

                }

                $order->fill(['total_price' => $totalPrice])->save();

            }
        }


        // foreach ($users as $user) {
        //     $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
        //     $userForms = CertificateForm::with('certificate')->where('user_id', $user->id)->get();

        //     if (isset($userForms)) {
        //         $totalPrice = 0;
        //         $deliveryPrice = 0;
        //         $delivered_by = rand(1,300);

        //         foreach ($userForms as $userForm) {
        //             $totalPrice = $totalPrice + $userForm->certificate->price;
        //             $deliveryFee = $userForm->certificate->delivery_fee;
        //             $deliveryPrice = $deliveryFee > $deliveryPrice && $deliveryFee;
        //         }

        //         $pickup = $pick_up_type[array_rand($pick_up_type)];
        //         $application = $application_status[array_rand($application_status)];
        //         $long = $faker->longitude();
        //         $lat = $faker->latitude();

        //         $order = Order::create([
        //             'ordered_by' => $user->id,
        //             'delivered_by' => $pickup === 'Delivery' ?  User::where('user_role_id', '=', 8)->get()->random()->id : NULL,
        //             'total_price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
        //             'pick_up_type' => $pickup,
        //             'delivery_fee' => $pickup === 'Delivery' ? $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34) : 0,
        //             'pickup_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //             'application_status' => $application,
        //             'order_status' => $application === 'Approved' ? 'Received' : 'Waiting',
        //             'location_address' => $faker->address(),
        //             'email' => $faker->lastName.$faker->email,
        //             'phone_no' => $faker->phoneNumber,
        //             'user_long' => $pickup === 'Delivery' ? $long : NULL,
        //             'user_lat' => $pickup === 'Delivery' ? $lat : NULL,
        //             'rider_long' => $pickup === 'Delivery' ? $long : NULL,
        //             'rider_lat' => $pickup === 'Delivery' ? $lat : NULL,
        //             'created_at' => $date,
        //             'updated_at' => $date,
        //         ]);

        //         if ($application === 'Denied') {
        //             CertificateForm::where('user_id', $user->id)
        //                 ->update(['status' => 'Denied']);
        //         }

        //         foreach ($userForms as $userForm) {
        //             CertificateFormOrder::create([
        //                 'order_id' => $order->id,
        //                 'certificate_form_id' => $userForm->id,
        //                 'created_at' => $date,
        //                 'updated_at' => $date,
        //             ]);
        //         }
        //     }
        // }
    }
}
