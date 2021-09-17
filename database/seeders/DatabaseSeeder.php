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
        $files = Storage::allFiles('public/reports/');
        Storage::delete($files);
        $files = Storage::allFiles('public/announcements/');
        Storage::delete($files);

        $this->call([
            PurokSeeder::class,
            UserRoleSeeder::class,
            UserSeeder::class,

            // Type Seeder
            FeedbackTypeSeeder::class,
            DocumentTypeSeeder::class,
            OrdinanceTypeSeeder::class,
            ComplaintTypeSeeder::class,
            ReportTypeSeeder::class,
            AnnouncementTypeSeeder::class,

            FeedbackSeeder::class,
            DocumentSeeder::class,
            OrdinanceSeeder::class,
            // ProjectSeeder::class,
            TermSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            MissingPersonSeeder::class,
            LostAndFoundSeeder::class,
            ComplaintSeeder::class,
            ReportSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
