<?php

namespace SeavSeyla\Announcements\Commands\Install;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeAnnouncementsFactory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:factory-install {--force : Overwrite the factory if it already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Announcements factory in the application.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the desired name from the argument
        $name = config('announcements.user_model', \App\Models\User::class);
        $model = '\\' . $name;
        $createBy = config('announcements.create_by_primary_key', 'user_id');

        // Determine the target path in the user's application
        // Using database_path() points to the 'database' directory of the user's Laravel project
        $targetPath = database_path("factories/" . "AnnouncementFactory.php");

        // --- Define the content of the file ---
        $stub = <<<EOT
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SeavSeyla\Announcements\Models\Announcement;

class AnnouncementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected \$model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => \$this->faker->sentence(),
            'content' => \$this->faker->paragraph(),
            'status' => \$this->faker->randomElement(['active', 'inactive', 'draft']),
            '{$createBy}' => $model::factory(),
        ];
    }
}

EOT;

        // --- Check if the file already exists ---
        if (File::exists($targetPath) && !$this->option('force')) {
            $this->error("Factory already exists at: {$targetPath}. Use --force to overwrite.");
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

        $this->info("Factory created successfully at: {$targetPath}");

        return 0; // Indicate success
    }
}
