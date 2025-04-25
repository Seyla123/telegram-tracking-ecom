<?php

namespace SeavSeyla\Announcements\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use SeavSeyla\Announcements\Database\Seeders\AnnouncementsSeeder;

class SeedAnnouncementsDirectly extends Command
{
    protected $signature = 'announcements:seed';

    protected $description = 'Seeds the database with initial announcements data directly from the package.';

    public function handle()
    {
        $this->info('Seeding announcements data directly from package...');

        // Disable mass assignment protection temporarily (common in seeders)
        Model::unguard();

        try {
            // Instantiate and call the seeder directly from your package's namespace
            (new AnnouncementsSeeder())->run();
            $this->info('Announcements data seeded successfully.');
        } catch (\Exception $e) {
            $this->error("Failed to seed announcements data: {$e->getMessage()}");
            return 1; // Indicate failure
        } finally {
            // Re-enable mass assignment protection
            Model::reguard();
        }

        return 0; // Indicate success
    }
}