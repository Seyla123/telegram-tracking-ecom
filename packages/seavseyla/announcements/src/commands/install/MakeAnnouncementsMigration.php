<?php

namespace SeavSeyla\Announcements\Commands\Install;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeAnnouncementsMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:migration-install {--force : Overwrite the seeder if it already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Announcements migrateration in the application.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the desired controller name from the argument
        $name = config('announcements.table_name', 'announcements');
        $createBy = config('announcements.create_by_table_name', 'user_id');


        // Determine the target path in the user's application
        // Using app_path() points to the 'app' directory of the user's Laravel project
        $targetPath = database_path("migrations/" . date('Y_m_d_His') . "_create_{$name}_table.php");

        // --- Define the content of the file ---
        // You can use a simple string or load from a stub file within your package
        $stub = <<<EOT
            <?php

            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Support\Facades\Schema;

            return new class extends Migration
            {
                /**
                 * Run the migrations.
                 */
                public function up(): void
                {
                    Schema::create('{$name}', function (Blueprint \$table) {
                        \$table->id();
                        \$table->string('title');
                        \$table->text('content');
                        \$table->enum('status', ['active', 'inactive', 'draft'])->default('active');
                        \$table->foreignId('{$createBy}')->nullable()->constrained();
                        \$table->timestamps();
                    });
                }

                /**
                 * Reverse the migrations
                 */
                public function down(): void
                {
                    Schema::dropIfExists('{$name}');
                }
            };

        EOT;

        // --- Check if the file already exists ---
        if (File::exists($targetPath) && !$this->option('force')) {
            $this->error("Migration already exists at: {$targetPath}");
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

        $this->info("Migration created successfully at: {$targetPath}");

        return 0; // Indicate success
    }
}
