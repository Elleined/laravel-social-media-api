<?php

namespace Database\Seeders;

use App\Models\ProviderType;
use App\Models\SocialUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providerTypes = ProviderType::query()->pluck('id');
        $usersWithoutPassword = User::query()->withTrashed()->whereNull('password')->pluck('id')->all();

        $socialUsers = array_map(fn($id) => [
            'provider_type_id' => $providerTypes->random(),
            'provider_id'      => fake()->uuid(),
            'user_id'          => $id,
            'created_at'       => now()   
        ], $usersWithoutPassword);
    
        SocialUser::insert($socialUsers);
    }
}
