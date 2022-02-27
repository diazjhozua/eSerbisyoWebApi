<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
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

        foreach(range(1,2) as $term) {
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

            $file_path = $pictures[array_rand($pictures)];
            $picture_name = 'barangay/'.uniqid().'-'.time();

            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => $term,
                'position_id' => 1,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);


            foreach (range(1,7) as $index) {

                $file_path = $pictures[array_rand($pictures)];
                $picture_name = 'barangay/'.uniqid().'-'.time();


                DB::table('employees')->insert([
                    'name' => $faker->name,
                    'term_id' => $term,
                    'position_id' => 2,
                    'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                    'picture_name' => $picture_name,
                    'file_path' => $file_path,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }

            $file_path = $pictures[array_rand($pictures)];
            $picture_name = 'barangay/'.uniqid().'-'.time();


            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => $term,
                'position_id' => 3,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            $file_path = $pictures[array_rand($pictures)];
            $picture_name = 'barangay/'.uniqid().'-'.time();

            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => $term,
                'position_id' => 4,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            $file_path = $pictures[array_rand($pictures)];
            $picture_name = 'barangay/'.uniqid().'-'.time();


            DB::table('employees')->insert([
                'name' => $faker->name,
                'term_id' => $term,
                'position_id' => 5,
                'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'picture_name' => $picture_name,
                'file_path' => $file_path,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            $file_path = $pictures[array_rand($pictures)];
            $picture_name = 'barangay/'.uniqid().'-'.time();


            foreach (range(1,7) as $index) {
                DB::table('employees')->insert([
                    'name' => $faker->name,
                    'term_id' => $term,
                    'position_id' => 6,
                    'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                    'picture_name' => $picture_name,
                    'file_path' => $file_path,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                 ]);
            }
        }
    }
}
