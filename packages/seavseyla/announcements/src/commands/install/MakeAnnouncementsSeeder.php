<?php

namespace SeavSeyla\Announcements\Commands\Install;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeAnnouncementsSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:seeder-install {--force : Overwrite the seeder if it already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Announcements Seeder in the application.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the desired name from the argument
        $name = config('announcements.create_by_model', \App\Models\User::class);
       
        $count = config('announcements.seed_count', 10);
        // Determine the target path in the user's application
        // Using database_path() points to the 'database' directory of the user's Laravel project
        $targetPath = database_path("seeders/" . "AnnouncementSeeder.php");

        // --- Define the content of the file ---
        $stub = <<<EOT
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use SeavSeyla\Announcements\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Announcement::factory()
            ->count($count)
            ->create();
    }
}

EOT;

        // --- Check if the file already exists ---
        if (File::exists($targetPath) && !$this->option('force')) {
            $this->error("Seeder already exists at: {$targetPath}. Use --force to overwrite.");
            return 1; // Indicate failure
        }

        // --- Create the directory if it doesn't exist ---
        // File::makeDirectory() with true arguments creates parent directories recursively
        $directory = dirname($targetPath);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
            $this->info("Created directory: {$directory}");
        }


        // --- Write the file content ---
        File::put($targetPath, $stub);

        $this->info("Seeder created successfully at: {$targetPath}");

        return 0; // Indicate success
    }
}
