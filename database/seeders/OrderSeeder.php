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
        activity()->disableLogging();
        $faker = \Faker\Factory::create();

        $users = User::where('is_verified', '=', 1)->get();
        $pick_up_type = ['Walkin','Pickup', 'Delivery'];
        $application_status = ['Approved', 'Denied'];
        $fake_address = [
            '46 Millionaire Street Purok 3 Cupang Muntinlupa City',
            '633 Purok 5 Cupang Muntinlupa City',
            '568 San Simon Purok 4 Cupang Muntinlupa City',
            '554 Purok 4 Cupang Muntinlupa City',
            '718 purok 6 Cupang Muntinlupa city.'
        ];

        $files = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946948/sample/orders/order1_zwjr5o.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946946/sample/orders/order17_pwho5a.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946943/sample/orders/order16_msrlqd.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946942/sample/orders/order3_wuyv7g.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946942/sample/orders/order19_ew4hhp.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946941/sample/orders/order18_dpawp1.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946939/sample/orders/order12_zk0xlg.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946939/sample/orders/order13_awvkpq.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946939/sample/orders/order14_ktj42a.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946938/sample/orders/order15_eflmhr.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946936/sample/orders/order11_arscqn.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946936/sample/orders/order4_qt4bln.png',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946934/sample/orders/order10_j7ia3z.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946933/sample/orders/order8_shgbjd.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946932/sample/orders/order20_toxzqp.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946927/sample/orders/order9_zydvgn.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946925/sample/orders/order7_jo5fxs.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946925/sample/orders/order6_zcqyfc.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946924/sample/orders/order5_bfcdy3.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645946924/sample/orders/order2_pes97s.jpg',
        ];

        foreach ($users as $user) {

            // random number of orders
            for($i = 0; $i <= $faker->numberBetween(1, 1); $i++) {
                $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null);
                $application = $application_status[array_rand($application_status)];
                $pickup = $pick_up_type[array_rand($pick_up_type)];
                $address = $fake_address[array_rand($fake_address)];

                $fileName = null;
                $filePath = null;

                if ($pickup == "Delivery") {

                    $filePath = $files[array_rand($files)];
                    $fileName = 'barangay/'.uniqid().'-'.time();
                }

                $admin_message = null;
                if ($application != 'Pending') {
                    $admin_message = $faker->realText($maxNbChars = 50, $indexSize = 1);
                }

                // $pickupDate = $faker->date($format = 'Y-m-d', $max = 'now');
                $pickupDate = $faker->dateTimeBetween($startDate = 'now', $endDate = '+5 days', $timezone = null);

                $order = Order::create([
                    'ordered_by' => $user->id,
                    'delivered_by' => $pickup === 'Delivery' ?  User::where('user_role_id', '=', 8)->get()->random()->id : NULL,
                    // 'total_price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                    'name' => $faker->name,
                    'pick_up_type' => $pickup,
                    'delivery_fee' => $pickup === 'Delivery' ? 60 : 0,
                    'pickup_date' => $pickupDate,
                    'received_at' => $pickup === 'Delivery' ? $pickupDate : $pickupDate,
                    'application_status' => $application,
                    'delivery_payment_status' => $pickup === 'Delivery' ? 'Received' : NULL,
                    'order_status' => $application === 'Approved' ? 'Received' : 'Waiting',
                    'location_address' => $address,
                    'email' => $faker->lastName.$faker->freeEmail,
                    'phone_no' => "09560492498",
                    'file_name' => $pickup === 'Delivery' ? $fileName : NULL,
                    'file_path' => $pickup === 'Delivery' ? $filePath : NULL,
                    'admin_message' => $admin_message,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                $totalPrice = 0;

                $fakur = \Faker\Factory::create();

                for ($i = 0; $i <= $faker->numberBetween(1, 2); $i++) {
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

                    CertificateOrder::insert([
                        'order_id' => $order->id,
                        'certificate_form_id' => $certificateForm->id,
                    ]);

                }

                $order->fill(['total_price' => $totalPrice])->save();

            }
        }
    }
}
