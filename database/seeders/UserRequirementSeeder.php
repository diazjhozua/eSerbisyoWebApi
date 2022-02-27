<?php

namespace Database\Seeders;

use App\Models\Requirement;
use App\Models\User;
use App\Models\UserRequirement;
use Illuminate\Database\Seeder;
use Log;

class UserRequirementSeeder extends Seeder
{

    public function run()
    {
        activity()->disableLogging();
        $users = User::where('is_verified', '=', 1)->get();
        $faker = \Faker\Factory::create();

        $requirements = Requirement::get();

        $pictures = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935350/sample/credentials/valid18_ghr5do.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935351/sample/credentials/valid1_ozqxmz.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935354/sample/credentials/valid20_phqc88.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935353/sample/credentials/valid16_sigk9p.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935347/sample/credentials/valid19_yn2a5u.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935354/sample/credentials/valid14_idqn8y.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935353/sample/credentials/valid7_tf8kjw.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935347/sample/credentials/valid17_f3zriy.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935343/sample/credentials/valid8_uezowa.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935345/sample/credentials/valid15_hckcsu.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935346/sample/credentials/valid10_dfvbz5.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935341/sample/credentials/valid12_ocps8p.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935341/sample/credentials/valid13_uwhaxp.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935340/sample/credentials/valid6_p6kdkm.png',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935341/sample/credentials/valid11_datl2p.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935338/sample/credentials/valid5_tinsgc.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935338/sample/credentials/valid4_pltqxe.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935338/sample/credentials/valid9_tblhwg.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935337/sample/credentials/valid3_ocfaay.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935340/sample/credentials/valid2_vcbptf.jpg',

            // permits
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

        foreach ($users as $user) {
            foreach ($requirements as $requirement) {
                $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null);
                $file_path = $pictures[array_rand($pictures)];
                $file_name = 'barangay/'.uniqid().'-'.time();

                UserRequirement::create([
                    'user_id' => $user->id,
                    'requirement_id' => $requirement->id,
                    'file_name' => $file_name,
                    'file_path' => $file_path,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

            }
        }


    }
}
