<?php

namespace Database\Factories;

use App\Models\ProviderType;
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
            'provider_type_id' => ProviderType::query()->pluck('id')->random(),
            'provider_id' => fake()->unique()->word(),
            'user_id' => User::query()->whereNull(['password'])->value('id')
        ];
    }
}
