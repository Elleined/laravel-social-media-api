<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Emoji;
use App\Models\Post;
use App\Models\PostReaction;
use App\Models\SocialUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(30)->create();
    }
}
