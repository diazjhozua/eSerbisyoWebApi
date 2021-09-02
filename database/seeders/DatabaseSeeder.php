<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // Get all files in a directory
        $files =   Storage::allFiles('private/signatures/');
        Storage::delete($files);
        $this->call([
            PurokSeeder::class,
            UserRoleSeeder::class,
            UserSeeder::class,
            FeedbackTypeSeeder::class,
            FeedbackSeeder::class,
            DocumentTypeSeeder::class,
            DocumentSeeder::class,
            OrdinanceCategorySeeder::class,
            OrdinanceSeeder::class,
            ProjectSeeder::class,
            TermSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            MissingPersonSeeder::class,
            LostAndFoundSeeder::class,
            ComplaintTypeSeeder::class,
            ComplaintSeeder::class,
            AnnouncementTypeSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
