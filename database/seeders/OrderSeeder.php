<?php

namespace Database\Seeders;

use App\Models\CertificateForm;
use App\Models\CertificateFormOrder;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Models\Request;
use App\Models\RequestRequirement;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{

    public function run()
    {

        $faker = \Faker\Factory::create();

        $users = User::all();
        $pick_up_type = ['Pickup', 'Delivery'];
        $application_status = ['Approved', 'Denied'];

        foreach ($users as $user) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $userForms = CertificateForm::with('certificate')->where('user_id', $user->id)->get();

            if (isset($userForms)) {
                $totalPrice = 0;
                $deliveryPrice = 0;
                $delivered_by = rand(1,37);

                foreach ($userForms as $userForm) {
                    $totalPrice = $totalPrice + $userForm->certificate->price;
                    $deliveryFee = $userForm->certificate->delivery_fee;
                    $deliveryPrice = $deliveryFee > $deliveryPrice && $deliveryFee;
                }

                $pickup = $pick_up_type[array_rand($pick_up_type)];
                $application = $application_status[array_rand($application_status)];
                $long = $faker->longitude();
                $lat = $faker->latitude();

                $order = Order::create([
                    'ordered_by' => $user->id,
                    'delivered_by' => $pickup === 'Delivery' ? $delivered_by : NULL,
                    'total_price' => $totalPrice,
                    'pick_up_type' => $pickup,
                    'delivery_fee' => $pickup === 'Delivery' ? $deliveryPrice : 0,
                    'pickup_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'application_status' => $application,
                    'order_status' => $application === 'Approved' ? 'Received' : NULL,
                    'location_address' => $faker->address(),
                    'user_long' => $pickup === 'Delivery' ? $long : NULL,
                    'user_lat' => $pickup === 'Delivery' ? $lat : NULL,
                    'rider_long' => $pickup === 'Delivery' ? $long : NULL,
                    'rider_lat' => $pickup === 'Delivery' ? $lat : NULL,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                if ($application === 'Denied') {
                    CertificateForm::where('user_id', $user->id)
                        ->update(['status' => 'Denied']);
                }

                foreach ($userForms as $userForm) {
                    CertificateFormOrder::create([
                        'order_id' => $order->id,
                        'certificate_form_id' => $userForm->id,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }
            }
        }
    }
}
