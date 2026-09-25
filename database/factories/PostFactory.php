<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'attachment' => fake()->optional()->word(),
            'deleted_at' => fake()->boolean() ? Carbon::parse(fake()->date()) : null,
            'author_id' => User::withTrashed()->pluck('id')->random(),
        ];
    }
}
