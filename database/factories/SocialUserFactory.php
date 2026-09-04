<?php

namespace Database\Factories;

use App\Models\SocialUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialUser>
 */
class SocialUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_type' => fake()->randomElement(['Google', 'Facebook', 'Microsoft']),
            'provider_id' => fake()->word(),
            'user_id' => User::factory()
        ];
    }
}
