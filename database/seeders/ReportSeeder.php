<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
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

        $status = ['Pending', 'Ignored', 'Invalid', 'Noted'];
        $urgency_classification = ['Nonurgent', 'Urgent'];

        $pictures = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944021/sample/reports/report6_n7jyxi.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944025/sample/reports/report4_tf4v3r.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944036/sample/reports/report1_dndhal.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944020/sample/reports/report5_m9up9d.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944020/sample/reports/images_xjekor.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944024/sample/reports/report3_j5f99y.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944022/sample/reports/report8_snte9w.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944021/sample/reports/report2_koaav3.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944018/sample/reports/images_3_ipljkh.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944018/sample/reports/images_6_ponsqb.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944018/sample/reports/images_7_dtn4f3.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944018/sample/reports/images_5_melqnp.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944018/sample/reports/images_2_s3pg9y.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944018/sample/reports/images_4_ltdvlr.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944018/sample/reports/images_1_vwaiah.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944017/sample/reports/download_2_pfmpmo.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944016/sample/reports/download_1_edvuf8.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944017/sample/reports/download_9_htkdff.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944017/sample/reports/report7_k2ofeb.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944017/sample/reports/download_to73np.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944017/sample/reports/download_3_cz2xfy.jpg',

        ];

        foreach (range(1,900) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null);
            $type = $faker->numberBetween(41, 45);
            $custom_type = NULL;
            $isNull = $faker->numberBetween(0,1);

            if ($isNull === 0) {
                $type = NULL;
                $custom_type = $faker->realText($maxNbChars = 10, $indexSize = 1);
            }

            $picture_availability =  $faker->numberBetween(0, 1);
            $picture_name = NULL;
            $file_path = NULL;

            if ($picture_availability == 1) {
                $file_path = $pictures[array_rand($pictures)];
                $picture_name = 'barangay/'.uniqid().'-'.time();
            }

            $admin_message = NULL;
            $final_status = $status[array_rand($status)];


            if ($final_status === 'Noted' || $final_status === 'Invalid') {
                $admin_message = $faker->realText($maxNbChars = 50, $indexSize = 1);
            }

            DB::table('reports')->insert([
                'user_id' => $faker->numberBetween(1, 133),
                'type_id' => $type,
                'custom_type' => $custom_type,
                'location_address' => $faker->streetAddress(),
                'landmark' => $faker->streetName(),
                'description' => $faker->realText($maxNbChars = 150, $indexSize = 3),
                'admin_message' => $admin_message,
                'is_anonymous' => $faker->numberBetween(0, 1),
                'urgency_classification' => $urgency_classification[array_rand($urgency_classification)],
                'status' => $final_status,
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
