<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintSeeder extends Seeder
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

        $files = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943479/sample/signature/images_nf1h5j.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943479/sample/signature/images_10_lsfro7.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943479/sample/signature/images_14_lltgav.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943479/sample/signature/images_13_amzhwe.png',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943479/sample/signature/images_12_mbukum.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943479/sample/signature/images_11_clj7ev.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943478/sample/signature/images_9_vyvu0d.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943478/sample/signature/images_6_l6scxl.png',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943478/sample/signature/images_7_iziwbt.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943478/sample/signature/images_5_fyrq98.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943478/sample/signature/images_8_ggtlwo.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943478/sample/signature/images_3_nsg1zk.png',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943478/sample/signature/images_2_kfgkyu.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943477/sample/signature/images_1_cclhaz.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943478/sample/signature/images_4_ogwm8t.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943477/sample/signature/images_1_k03dga.jpg',

        ];

        $statusArr = ['Pending', 'Denied', 'Approved', 'Resolved'];

        foreach (range(1,200) as $complaint_id) {
            $type = $faker->numberBetween(36, 40);
            $custom_type = NULL;

            $isNull = $faker->numberBetween(0,1);

            if ($isNull === 0) {
                $type = NULL;
                $custom_type = $faker->realText($maxNbChars = 10, $indexSize = 1);
            }

            $admin_message = null;

            $status = $statusArr[array_rand($statusArr)];

            if ($status != 'Pending') {
                $admin_message = $faker->realText($maxNbChars = 50, $indexSize = 1);
            }

            $complainantCount = $faker->numberBetween(1, 2);
            $defendantCount = $faker->numberBetween(1, 5);
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null);
            $userID = $faker->numberBetween(1, 133);
            DB::table('complaints')->insert([
                'user_id' => $userID,
                'contact_user_id' => $userID,
                'type_id' => $type,
                'custom_type' => $custom_type,
                'reason' => $faker->realText($maxNbChars = 500, $indexSize = 3),
                'action' => $faker->realText($maxNbChars = 500, $indexSize = 3),
                'email' => $faker->lastName.$faker->email,
                'phone_no' => $faker->phoneNumber,
                'status' => $status,
                'admin_message' => $admin_message,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            foreach (range(1, $complainantCount) as $index) {
                $file_path = $files[array_rand($files)];
                $file_name = 'barangay/'.uniqid().'-'.time();

                DB::table('complainants')->insert([
                    'complaint_id' => $complaint_id,
                    'name' => $faker->name(),
                    'signature_picture' => $file_name,
                    'file_path' => $file_path,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            foreach (range(1, $defendantCount) as $index) {
                DB::table('defendants')->insert([
                    'complaint_id' => $complaint_id,
                    'name' => $faker->name(),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

        }
    }
}
