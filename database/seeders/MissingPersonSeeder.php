<?php

namespace Database\Seeders;

use App\Models\MissingPerson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissingPersonSeeder extends Seeder
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

        $height_scale = ['feet(ft)', 'centimeter(cm)'];
        $weight_scale = ['kilogram(kg)', 'pound(lbs)'];

        $statusArr = ['Pending', 'Denied', 'Approved', 'Resolved'];
        $report_type = ['Missing', 'Found'];

        $pictures = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934206/sample/humanFaces/images_6_bpxsqj.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934206/sample/humanFaces/images_myyvht.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934206/sample/humanFaces/images_5_typefl.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934206/sample/humanFaces/images_4_irqxfb.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934206/sample/humanFaces/images_2_sh8zjk.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934206/sample/humanFaces/images_3_hs6nvc.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934206/sample/humanFaces/images_1_rener7.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_acdixz.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_16_yd8qsz.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_14_tmh02n.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_13_ugpgkt.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_12_jb4s1j.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_11_qoeahe.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_10_vjfbot.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_8_albffz.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_9_gduz2w.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934205/sample/humanFaces/download_7_emajte.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934204/sample/humanFaces/download_6_u6jsst.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934204/sample/humanFaces/download_5_xsbyzu.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934204/sample/humanFaces/download_4_mpchfy.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934204/sample/humanFaces/download_3_a8cpvk.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934204/sample/humanFaces/download_2_ks8q0i.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645934204/sample/humanFaces/download_1_cuif3e.jpg',
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
            $missingPerson = MissingPerson::create([
                'user_id' => $userID,
                'contact_user_id' => $userID,
                'name' => $faker->name(),
                'height' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1.6, $max = 8),
                'height_unit' => $height_scale[array_rand($height_scale)],
                'weight' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120.34),
                'weight_unit' => $weight_scale[array_rand($weight_scale)],
                'age' => $faker->numberBetween(1,120),
                'eyes' => $faker->colorName(),
                'hair' => $faker->colorName(),
                'unique_sign' => $faker->sentence($nbWords = 4, $variableNbWords = true),
                'important_information' => $faker->sentence($nbWords = 10, $variableNbWords = true),
                'last_seen' => $faker->streetName(),
                'email' => $faker->lastName.$faker->email,
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
                $missingPerson->comments()->create([
                    'user_id' => $faker->numberBetween(1,63),
                    'body' => $faker->realText($maxNbChars = 100, $indexSize = 3)
                ]);
            }

        }
    }
}
