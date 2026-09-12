<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Seeder;

class SocialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $providerTypes = DB::table('ref_provider_types')->pluck('id');
        $usersWithoutPassword = User::query()->withTrashed()->whereNull('password')->pluck('id')->all();

        $socialUsers = array_map(fn ($id) => [
            'provider_type_id' => $providerTypes->random(),
            'provider_id' => fake()->uuid(),
            'user_id' => $id,
            'created_at' => now(),
        ], $usersWithoutPassword);

        DB::table('social_users')->insert($socialUsers);
    }
}
