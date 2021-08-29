<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            AnnouncementTypeSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
