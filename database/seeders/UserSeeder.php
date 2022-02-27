<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
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

        $firstName = array('Jayson', 'Jose', 'Cassidy', 'Mark Joseph', 'Marlou',
        'Jhay-R', 'Jhozua', 'Lou', 'Mark Joseph', 'Eileen',
        'Ezekiel', 'Princess', 'Zam', 'Angelo', 'Albert',
        'Kier', 'Alexander', 'Clariz', 'Carl');

        $lastName = array('Aguilando', 'Arboleda', 'Carbajosa', 'Casas', 'Cerilla',
        'Cortez', 'Diaz', 'Gongob', 'Guerrero', 'Huang',
        'Lacbayen', 'Leano', 'Magallanes', 'Mijares', 'Mituda',
        'Sales', 'Udag', 'Velez', 'Villanueva');

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


        $position = [
            1 => 'superadmin',
            2 => 'informationadmin',
            3 => 'certificationadmin',
            4 => 'taskforceadmin',
            5 => 'informationstaff',
            6 => 'certificationstaff',
            7 => 'taskforcestaff',
            8 => 'biker',
            9 => 'resident',
        ];

        foreach (range(1,9) as $positionNum) {
            foreach(range(1,2) as $index) {
                $file_path = $pictures[array_rand($pictures)];
                $picture_name = 'barangay/'.uniqid().'-'.time();
                $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
                DB::table('users')->insert([
                    'first_name' =>  $faker->firstName,
                    'middle_name' => $faker->lastName,
                    'last_name' => $faker->lastName,
                    'email' => $position[$positionNum].$index.'@gmail.com',
                    'phone_no' => "09560492498",
                    'password' => Hash::make('12341234'),
                    'picture_name' => $picture_name,
                    'file_path' => $file_path,
                    'purok_id' => $faker->numberBetween(1,5),
                    'address' => $faker->address,
                    'is_verified' => true,
                    'status' => 'Enable',
                    'user_role_id' => $positionNum,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }
        }

        // For biker
        foreach (range(1,20) as $index)
        {
            $file_path = $pictures[array_rand($pictures)];
            $picture_name = 'barangay/'.uniqid().'-'.time();
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
	        DB::table('users')->insert([
                'first_name' =>  $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => strtolower($faker->firstName.$faker->lastName.$faker->buildingNumber).'@gmail.com',
                'phone_no' => "09560492498",
                'password' => Hash::make('12341234'),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'Enable',
                'user_role_id' => 8,
                'bike_type' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'bike_color' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'bike_size' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }


        foreach (range(6,19) as $index)
        {
            $file_path = $pictures[array_rand($pictures)];
            $picture_name = 'barangay/'.uniqid().'-'.time();
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

	        DB::table('users')->insert([
                'first_name' =>  $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => strtolower($faker->firstName.$faker->lastName.$faker->buildingNumber).'@gmail.com',
                'phone_no' => "09560492498",
                'password' => Hash::make('12341234'),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => true,
                'status' => 'Enable',
                'user_role_id' => 9,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }

        foreach (range(20,100) as $index)
        {
            $file_path = $pictures[array_rand($pictures)];
            $picture_name = 'barangay/'.uniqid().'-'.time();
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

	        DB::table('users')->insert([
                'first_name' =>  $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => strtolower($faker->firstName.$faker->lastName.$faker->buildingNumber).'@gmail.com',
                'phone_no' => "09560492498",
                'password' => Hash::make('12341234'),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'purok_id' => $faker->numberBetween(1,5),
                'address' => $faker->address,
                'is_verified' => false,
                'status' => 'Enable',
                'user_role_id' => 9,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }

        $credentials = [
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
        ];

        $unverifiedUsers = User::where('is_verified', 0)->get();
        foreach ($unverifiedUsers as $user) {

            $credentials_file_path = $credentials[array_rand($credentials)];
            $credentials_name = 'barangay/'.uniqid().'-'.time();
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

	        DB::table('user_verifications')->insert([
                'user_id' =>  $user->id,
                'credential_name' => $credentials_name,
                'credential_file_path' => $credentials_file_path,
                'admin_message' => '',
                'status' => 'Pending',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
	        ]);
        }

        $bikes = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935899/sample/bikes/bike10_yowmaw.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935863/sample/bikes/bike12_vdh4gl.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935862/sample/bikes/bike3_xqlusb.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935860/sample/bikes/bike9_hbndqg.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935859/sample/bikes/bike19_n8s4qv.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935859/sample/bikes/bike7_hkotuw.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935858/sample/bikes/bike20_uwwfbd.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935854/sample/bikes/bike18_ccfopk.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935851/sample/bikes/bike17_ocjove.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935849/sample/bikes/bike13_qmr2pv.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935848/sample/bikes/bike16_znodvs.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935848/sample/bikes/bike4_ky94nt.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935847/sample/bikes/bike15_gymnba.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935844/sample/bikes/bike1_vc2nrn.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935844/sample/bikes/bike14_kpjnzy.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935843/sample/bikes/bike5_djtqc3.jpg',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935843/sample/bikes/bike8_zwbcua.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935841/sample/bikes/bike11_bga5nr.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935840/sample/bikes/bike6_aac9ur.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645935839/sample/bikes/bike2_zcth2p.jpg',
        ];

        // for biker request application
        foreach (range(1,80) as $index)
        {
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $credentials_file_path = $bikes[array_rand($bikes)];
            $credentials_name = 'barangay/'.uniqid().'-'.time();
            DB::table('biker_requests')->insert([
                'user_id' => $faker->numberBetween(20, 100),
                'phone_no' => "09560492498",
                'bike_type' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'bike_color' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'bike_size' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'reason' => $faker->realText($maxNbChars = 150, $indexSize = 2),
                'credential_name' => $credentials_name,
                'credential_file_path' => $credentials_file_path,
                'admin_message' => '',
                'status' => 'Pending',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
