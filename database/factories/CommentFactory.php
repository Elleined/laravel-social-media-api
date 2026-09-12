<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph(),
            'attachment' => fake()->optional()->word(),
            'deleted_at' => Carbon::parse(fake()->optional()->date()),
            'author_id' => User::query()->withTrashed()->pluck('id')->random(),
            'post_id' => Post::query()->withTrashed()->pluck('id')->random(),
        ];
    }
}
