<?php

namespace SeavSeyla\Announcements\Commands\Install;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeAnnouncementsAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:install {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates essential files (Seeder, Factory, Migration) for the Announcements package in the application.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating essential files for SeavSeyla Announcements package...');

        // Determine if the --force option was used
        $forceOption = $this->option('force');

        // --- Call the MakeAnnouncementsSeeder command ---
        $this->comment('Creating Announcements Seeder...');
        // Prepare arguments for the seeder command
        $seederArgs = [];
        if ($forceOption) {
            $seederArgs['--force'] = true;
        }
        // Call the seeder command internally
        $exitCodeSeeder = Artisan::call('announcements:seeder-install', $seederArgs, $this->getOutput());

        // Check the exit code of the seeder command
        if ($exitCodeSeeder !== 0) {
            $this->error('Failed to create Announcements Seeder.');
            // Optionally stop the process here or continue
            // return 1; // Uncomment to stop on first failure
        } else {
             $this->info('Announcements Seeder creation finished.');
        }


        // --- Call the MakeAnnouncementsFactory command ---
        $this->comment('Creating Announcements Factory...');
        // Prepare arguments for the factory command
        $factoryArgs = [];
        if ($forceOption) {
            $factoryArgs['--force'] = true;
        }
         // Call the factory command internally
        $exitCodeFactory = Artisan::call('announcements:factory-install', $factoryArgs, $this->getOutput()); 

        // Check the exit code of the factory command
        if ($exitCodeFactory !== 0) {
            $this->error('Failed to create Announcements Factory.');
             // Optionally stop the process here or continue
             // return 1; // Uncomment to stop on first failure
        } else {
             $this->info('Announcements Factory creation finished.');
        }

        // --- Call the MakeAnnouncementsMigration command ---
        $this->comment('Creating Announcements Migration...');
        // Prepare arguments for the migration command
        $migrationArgs = [];
        if ($forceOption) {
            $migrationArgs['--force'] = true;
        }
        // Call the migration command internally
        $exitCodeMigration = Artisan::call('announcements:migration-install', $migrationArgs, $this->getOutput());

        // Check the exit code of the migration command
        if ($exitCodeMigration !== 0) {
            $this->error('Failed to create Announcements Migration.');
             // Optionally stop the process here or continue
             // return 1; // Uncomment to stop on first failure
        } else {
             $this->info('Announcements Migration creation finished.');
        }

        // --- (Optional) Handle Migrations ---
        // As discussed, migrations are typically *published* and then *run*.
        // If you want this command to also handle migrations, you would call:
        // $this->comment('Publishing and running Announcements Migrations...');
        // $migrateArgs = [];
        // if ($forceOption) {
        //     $migrateArgs['--force'] = true; // Pass --force to the migrate command if needed
        // }
        // // Call your announcements:migrate command (if you created one)
        // $exitCodeMigrate = Artisan::call('announcements:migrate', $migrateArgs, $this->getOutput());
        // // OR call the standard publish and migrate commands directly:
        // // Artisan::call('vendor:publish', ['--provider' => 'SeavSeyla\Announcements\AnnouncementPackageServiceProvider', '--tag' => 'announcements-migrations', '--force' => $forceOption], $this->getOutput());
        // // Artisan::call('migrate', $migrateArgs, $this->getOutput());


        $this->info('Essential files creation process completed.');

        // Return a non-zero exit code if any step failed (if you didn't return earlier)
        if ($exitCodeSeeder !== 0 || $exitCodeFactory !== 0 || $exitCodeMigration !== 0) { // Add other exit codes here if you include migrations etc.
            return 1;
        }


        return 0; // Indicate overall success
    }
}

