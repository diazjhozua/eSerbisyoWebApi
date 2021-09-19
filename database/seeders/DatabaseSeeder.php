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
        // Delete all files
        $files = Storage::allFiles('public/signatures/');
        Storage::delete($files);
        $files = Storage::allFiles('public/documents/');
        Storage::delete($files);
        $files = Storage::allFiles('public/employees/');
        Storage::delete($files);
        $files = Storage::allFiles('public/missing-pictures/');
        Storage::delete($files);
        $files = Storage::allFiles('public/ordinances/');
        Storage::delete($files);
        $files = Storage::allFiles('public/users/');
        Storage::delete($files);
        $files = Storage::allFiles('public/projects/');
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
