<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        foreach (range(1,100) as $index) {
            $announcementTypeId = $faker->numberBetween(31,35);
            $date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);

            $announcement = new Announcement();
            $announcement->type_id = $announcementTypeId;
            $announcement->title = $faker->words($nb = 5, $asText = true);
            $announcement->description = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
            $announcement->created_at = $date;
            $announcement->updated_at = $date;
            $announcement->save();

            $pictureCount = $faker->numberBetween(1, 5);

            foreach (range(1, $pictureCount) as $index) {
                $picture = $faker->file($sourceDir = 'C:\Project Assets\AppAnnouncements', $targetDir = 'C:\xampp\htdocs\barangay-app\storage\app\public\announcements', false);
                $file_path = 'public/announcements/'.$picture;
                DB::table('announcement_pictures')->insert([
                    'announcement_id' => $announcement->id,
                    'picture_name' => $picture,
                    'file_path' => $file_path,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            $commentCount = $faker->numberBetween(1,10);

            foreach (range(1, $commentCount) as $index) {
                $announcement->comments()->create([
                    'user_id' => $faker->numberBetween(1,19),
                    'body' => $faker->realText($maxNbChars = 100, $indexSize = 3)
                ]);
            }


        }
    }
}
