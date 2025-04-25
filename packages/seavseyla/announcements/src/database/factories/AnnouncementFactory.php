<?php

namespace SeavSeyla\Announcements\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SeavSeyla\Announcements\Models\Announcement;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\SeavSeyla\Announcements\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userModel = config('announcements.user_model', \App\Models\User::class);
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'draft']),
            config('announcements.create_by_table_name', 'user_id') => $userModel::factory(),
        ];
    }
}

