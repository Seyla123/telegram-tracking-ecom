<?php

namespace SeavSeyla\Announcements;

use Illuminate\Support\ServiceProvider;
use SeavSeyla\Announcements\Commands\SeedAnnouncementsDirectly;
use SeavSeyla\Announcements\Commands\PublishAnnouncementsSeeders;
use SeavSeyla\Announcements\Commands\Install\MakeAnnouncementsAll;
use SeavSeyla\Announcements\Commands\Install\MakeAnnouncementsSeeder;
use SeavSeyla\Announcements\Commands\Install\MakeAnnouncementsFactory;
use SeavSeyla\Announcements\Commands\Install\MakeAnnouncementsMigration;

class AnnouncementPackageServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load routes from the package.
        // There are 2 files, one for web routes and one for Backpack\CRUD custom routes.
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/backpack/custom.php');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadViewsFrom(__DIR__ . '/views', 'view');


        if ($this->app->runningInConsole()) {
            // Define what files can be published under the 'announcements-seeders' tag
            $this->publishes([
                __DIR__ . '/database/seeders/' => database_path('seeders/'),
                __DIR__ . '/database/factories/' => database_path('factories'),
                // Add other seeders/factories here
            ], 'announcements-seeders'); // <-- The tag used in the command

            $this->publishes([
                __DIR__ . '/database/migrations/' => database_path('migrations')
            ], 'announcements-migrations');

            // Publish seeders (optional)
            $this->publishes([
                __DIR__ . '/database/seeders/' => database_path('seeders')
            ], 'announcements-seeders');

            // Publish factories (optional)
            $this->publishes([
                __DIR__ . '/database/factories/' => database_path('factories')
            ], 'announcements-factories');
            // Publish config
            $this->publishes([
                __DIR__ . '/config/announcements.php' => config_path('announcements.php'),
            ], 'config');
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register your Artisan command
        $this->commands([
            PublishAnnouncementsSeeders::class,
            SeedAnnouncementsDirectly::class,
            MakeAnnouncementsMigration::class,
            MakeAnnouncementsFactory::class,
            MakeAnnouncementsSeeder::class,
            MakeAnnouncementsAll::class
        ]);

        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/config/announcements.php',
            'announcements'
        );

    }

}
