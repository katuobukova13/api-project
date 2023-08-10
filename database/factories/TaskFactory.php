<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => rand(0, 1) ? fake()->text() : null,
            'user_id' => User::inRandomOrder()->first()->id,
            'project_id' => Project::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(TaskStatus::cases()),
        ];
    }
}
