<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderReport;
use Illuminate\Database\Seeder;

class OrderReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        activity()->disableLogging();
        $orders = Order::where('pick_up_type', 'Delivery')->get();

        $faker = \Faker\Factory::create();

        foreach ($orders as $order) {
            OrderReport::create([
                'user_id' => $order->ordered_by,
                'order_id' => $order->id,
                'body' => $faker->realText($maxNbChars = 50, $indexSize = 1),
                'status' => "Pending",
                'created_at' => $order->created_at,
                'updated_at' => $order->created_at,
            ]);
        }
    }
}
