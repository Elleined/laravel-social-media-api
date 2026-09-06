<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProviderTypeSeeder::class,
            EmojiSeeder::class,
            UserSeeder::class,
            SocialUserSeeder::class,
            PostSeeder::class,
            CommentSeeder::class
        ]);
    }
}
