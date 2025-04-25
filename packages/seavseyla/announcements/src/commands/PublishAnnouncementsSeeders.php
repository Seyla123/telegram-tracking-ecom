<?php

namespace SeavSeyla\Announcements\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class PublishAnnouncementsSeeders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:publish {--force : Overwrite any existing files} {--seed : Seed the database with initial data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the SeavSeyla Announcements package: publishes assets and optionally seeds the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing SeavSeyla Announcements package...');

        // Define assets to publish (you can include config, migrations, views, etc.)
        $tagsToPublish = ['announcements-config', 'announcements-migrations', 'announcements-seeders']; // Add 'announcements-views', 'announcements-assets' if you create those tags

        // --- 1. Publish Package Assets ---
        $this->comment('Publishing package assets...');
        foreach ($tagsToPublish as $tag) {
            $this->line("Publishing tag: <info>{$tag}</info>");
             Artisan::call('vendor:publish', [
                 '--provider' => 'SeavSeyla\Announcements\AnnouncementPackageServiceProvider',
                 '--tag' => $tag,
                 '--force' => $this->option('force'), // Use the --force option from your command
             ], $this->getOutput()); // Pass the current output to see vendor:publish messages
        }

        $this->info('Package assets published.');


        // --- 2. Run Database Seeder (Optional) ---
        if ($this->option('seed')) {
             // IMPORTANT: Reference the seeder class using the application's namespace
             // because the file is now copied to the application's directory
             $seederClass = 'SeavSeyla\Announcements\Database\Seeders\AnnouncementsSeeder';

             // Check if the seeder file exists in the application's seeder directory
             // This check is important, especially if --force was NOT used during publish
             $seederPath = database_path('seeders/' . class_basename($seederClass) . '.php');

             if (File::exists($seederPath)) {
                 $this->comment("Running database seeder: <info>{$seederClass}</info>...");
                 try {
                     Artisan::call('db:seed', ['--class' => $seederClass], $this->getOutput());
                     $this->info('Database seeding completed.');
                 } catch (\Exception $e) {
                     $this->error("Failed to run seeder: {$e->getMessage()}");
                     $this->error("Please check your database connection and the seeder file content.");
                     return 1; // Indicate failure
                 }
             } else {
                  $this->error("Seeder file not found at '{$seederPath}'. Ensure it was published correctly.");
                  return 1; // Indicate failure
             }
        } else {
             $this->info('Skipping database seeding. Run `php artisan announcements:install --seed` to include seeding.');
        }

        $this->info('SeavSeyla Announcements package installed successfully!');
        return 0; // Indicate success
    }
}