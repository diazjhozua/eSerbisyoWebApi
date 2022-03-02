<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\AnnouncementPicture;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Log;
use SebastianBergmann\Diff\Chunk;

class AnnouncementSeeder extends Seeder
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

        $announcements = [];
        $announcements_pictures = [];
        $comments = [];
        $likes = [];
        $types = collect(Type::where('model_type', 'Announcement')->get()->modelKeys());
        $users = collect(User::all()->modelKeys());

        $pictures = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933702/sample/announcements/239177770_1363272047467659_3485339378647085940_n_bn6hn0.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933699/sample/announcements/234733869_1356132451514952_2258441310611338130_n_ni0uww.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933697/sample/announcements/234803160_1356132421514955_6976575858860693175_n_ddpvjc.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933698/sample/announcements/236003972_1363271950801002_6336315695078483869_n_ie4nut.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933691/sample/announcements/221941369_1356131824848348_1221159208474737355_n_fesbva.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933689/sample/announcements/221966899_1356132478181616_6475226044325174508_n_s2gdnr.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933688/sample/announcements/201128693_1317819118679619_7348517546837584890_n_jcz7cr.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933688/sample/announcements/196345308_1315780412216823_5479742304246549261_n_zbe1v7.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933686/sample/announcements/199800737_1317138348747696_4945706852732148296_n_yt2l6d.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933685/sample/announcements/234518499_1356132551514942_2863435425186471401_n_wgrsln.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933685/sample/announcements/195509883_1315780402216824_3937095973064181914_n_fok3ni.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933682/sample/announcements/233769829_1356132104848320_6788909663720578099_n_hhoiuo.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933684/sample/announcements/222344289_1356131051515092_2522446785076795624_n_eifmyr.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933680/sample/announcements/200057788_1317819028679628_5561900075902264430_n_q2ychw.jpg',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645933680/sample/announcements/230561063_1362471890881008_4594338312117820090_n_hlujmn.jpg',
        ];


        foreach (range(1,100) as $id) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null);
            //announcements
            $announcements[] = [
                'type_id' => $types->random(),
                'title' => $faker->words($nb = 5, $asText = true),
                'description' => $faker->paragraph($nbSentences = 15, $variableNbSentences = true),
                'created_at' => $date,
                'updated_at' => $date,
            ];

            //announcement_pictures
            for ($i = 0; $i <= $faker->numberBetween(1, 5); $i++) {

                $file_path = $pictures[array_rand($pictures)];
                $picture = 'barangay/'.uniqid().'-'.time();
                $announcements_pictures[] = [
                    'announcement_id' => $id,
                    'picture_name' => $picture,
                    'file_path' => $file_path,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }

            // announcement_comments
            $afterDate = $faker->dateTimeBetween($date, $date->format('Y-m-d H:i:s').' +10 days');

            for ($i = 0; $i <= $faker->numberBetween(1, 2); $i++) {
                $comments[] = [
                    'user_id' => $users->random(),
                    'body' => $faker->realText($maxNbChars = 40, $indexSize = 1),
                    'commentable_id' => $id,
                    'commentable_type' => 'App\Models\Announcement',
                    'created_at' => $afterDate,
                    'updated_at' => $afterDate,
                ];
            }

            for ($i = 0; $i < rand(1, 3); $i++) {
                $likes[] = [
                    'user_id' => $users->random(),
                    'likeable_id' => $id,
                    'likeable_type' => 'App\Models\Announcement',
                    'created_at' => $afterDate,
                    'updated_at' => $afterDate,
                ];
            }
        }

        $chunks = array_chunk($announcements, 20);
        foreach ($chunks as $chunk) {
            Announcement::insert($chunk);
        }

        $chunks = array_chunk($announcements_pictures, 1240);
        foreach ($chunks as $chunk) {
            AnnouncementPicture::insert($chunk);
        }

        $chunks = array_chunk($comments, 3000);
        foreach ($chunks as $chunk) {
            Comment::insert($chunk);
        }

        $chunks = array_chunk($likes, 13000);
        foreach ($chunks as $chunk) {
            Like::insert($chunk);
        }
    }
}
