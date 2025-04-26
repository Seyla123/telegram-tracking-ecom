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
        $createByModel = config('announcements.create_by_model', \App\Models\User::class);
        $createByPrimaryKey = config('announcements.create_by_primary_key', 'user_id');

        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'draft']),
            $createByPrimaryKey => $createByModel::factory(),
        ];
    }
}

