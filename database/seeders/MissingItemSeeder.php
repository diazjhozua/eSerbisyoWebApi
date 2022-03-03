<?php

namespace Database\Seeders;

use App\Models\MissingItem;
use Illuminate\Database\Seeder;

class MissingItemSeeder extends Seeder
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

        $statusArr = ['Pending', 'Denied', 'Approved', 'Resolved'];
        $report_type = ['Missing', 'Found'];

        $pictures = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943063/sample/items/download_1_kle6yk.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943063/sample/items/images_15_xf4ros.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943063/sample/items/images_16_yvaira.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943063/sample/items/images_14_yvzjg0.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943062/sample/items/images_9_g0seoo.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943062/sample/items/images_qeifzo.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943062/sample/items/images_8_t0icox.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943061/sample/items/download_5_o7mcfy.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943062/sample/items/images_11_d9ph00.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943062/sample/items/images_13_gmmsxs.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943062/sample/items/images_12_zgcf6s.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943062/sample/items/images_10_f21yst.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943060/sample/items/download_2_krxawy.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943061/sample/items/images_1_hsdgyl.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943061/sample/items/images_6_asksdc.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943061/sample/items/images_3_ilfgii.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943060/sample/items/download_rkf3wy.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943061/sample/items/images_5_hjgldp.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943061/sample/items/images_4_vmjfl8.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943061/sample/items/images_2_g8mw9z.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943060/sample/items/download_4_nsczhw.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943060/sample/items/download_3_frc2ye.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943061/sample/items/images_7_jqzvuv.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645943060/sample/items/download_6_jkghir.jpg',
        ];


        $credentials = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944757/sample/sampleCert/cert17_trywja.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944757/sample/sampleCert/cert16_tqrk2m.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944755/sample/sampleCert/cert18_paqm5v.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944755/sample/sampleCert/cert19_vcswc4.png',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944754/sample/sampleCert/cert3_ftfqav.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944754/sample/sampleCert/cert1_s9qtqg.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944753/sample/sampleCert/cert12_qkz7qy.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944749/sample/sampleCert/cert15_b0rnwo.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944749/sample/sampleCert/cert14_ocw79b.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944747/sample/sampleCert/cert10_u3qtln.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944746/sample/sampleCert/cert13_xkfshu.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944746/sample/sampleCert/cert11_mj2kyl.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944745/sample/sampleCert/cert2_cc1efl.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944744/sample/sampleCert/cert8_ij2c4g.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944744/sample/sampleCert/cert9_bv3nlc.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944744/sample/sampleCert/cert20_rqmxoj.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944741/sample/sampleCert/cert7_hfk9ju.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944741/sample/sampleCert/cert5_awp5cy.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645944738/sample/sampleCert/cert6_hwnfxd.jpg',
        ];

        foreach (range(1,100) as $index) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $file_path = $pictures[array_rand($pictures)];
            $picture_name = 'barangay/'.uniqid().'-'.time();

            $credentials_file_path = $credentials[array_rand($credentials)];
            $credentials_name = 'barangay/'.uniqid().'-'.time();

            $admin_message = null;

            $status = $statusArr[array_rand($statusArr)];

            if ($status != 'Pending') {
                $admin_message = $faker->realText($maxNbChars = 50, $indexSize = 1);
            }

            $userID = $faker->numberBetween(1, 63);

            $lostAndFound = MissingItem::create([
                'user_id' => $userID,
                'contact_user_id' => $userID,
                'item' => $faker->sentence($nbWords = 3, $variableNbWords = true),
                'last_seen' => $faker->streetName(),
                'description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
                'email' => $faker->lastName.$faker->freeEmail,
                'phone_no' => "09560492498",
                'status' => $status,
                'admin_message' => $admin_message,
                'report_type' => $report_type[array_rand($report_type)],
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'credential_name' => $credentials_name,
                'credential_file_path' => $credentials_file_path,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
                'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null)
            ]);

            $commentCount = $faker->numberBetween(1,4);

            foreach (range(1, $commentCount) as $index) {
                $lostAndFound->comments()->create([
                    'user_id' => $faker->numberBetween(1,63),
                    'body' => $faker->realText($maxNbChars = 100, $indexSize = 3)
                ]);
            }

        }
    }
}
