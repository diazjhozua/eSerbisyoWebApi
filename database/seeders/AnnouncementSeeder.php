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
        $faker = \Faker\Factory::create();
        $announcements = [];
        $pictures = [];
        $comments = [];
        $likes = [];
        $types = collect(Type::where('model_type', 'Announcement')->get()->modelKeys());
        $users = collect(User::all()->modelKeys());

        foreach (range(1,1000) as $id) {
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            //announcements
            $announcements[] = [
                'type_id' => $types->random(),
                'title' => $faker->words($nb = 5, $asText = true),
                'description' => $faker->paragraph($nbSentences = 15, $variableNbSentences = true),
                'created_at' => $date,
                'updated_at' => $date,
            ];

            // //announcement_pictures
            for ($i = 0; $i <= $faker->numberBetween(1, 5); $i++) {
                $picture = $faker->file($sourceDir = 'C:\Project Assets\AppAnnouncements', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\announcements', false);
                $file_path = 'announcements/'.$picture;

                $pictures[] = [
                    'announcement_id' => $id,
                    'picture_name' => $picture,
                    'file_path' => $file_path,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }

            // announcement_comments
            $afterDate = $faker->dateTimeBetween($date, $date->format('Y-m-d H:i:s').' +10 days');

            for ($i = 0; $i <= $faker->numberBetween(1, 10); $i++) {
                $comments[] = [
                    'user_id' => $users->random(),
                    'body' => $faker->realText($maxNbChars = 40, $indexSize = 1),
                    'commentable_id' => $id,
                    'commentable_type' => 'App\Models\Announcement',
                    'created_at' => $afterDate,
                    'updated_at' => $afterDate,
                ];
            }

            for ($i = 0; $i < rand(1, $users->count()); $i++) {
                $likes[] = [
                    'user_id' => $users->random(),
                    'likeable_id' => $id,
                    'likeable_type' => 'App\Models\Announcement',
                    'created_at' => $afterDate,
                    'updated_at' => $afterDate,
                ];
            }
        }

        $chunks = array_chunk($announcements, 400);
        foreach ($chunks as $chunk) {
            Announcement::insert($chunk);
        }

        $chunks = array_chunk($pictures, 1240);
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
