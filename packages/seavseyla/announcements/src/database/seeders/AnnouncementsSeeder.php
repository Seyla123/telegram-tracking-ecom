<?php

namespace SeavSeyla\Announcements\Database\Seeders;

use Illuminate\Database\Seeder;
use SeavSeyla\Announcements\Models\Announcement;

class AnnouncementsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        logger(12345678);
        \Log::channel('asdfgh')->info('Something happened!');
        $count = config('announcements.seed_count', 10);

        Announcement::factory()
            ->count($count)
            ->create();
    }
}
