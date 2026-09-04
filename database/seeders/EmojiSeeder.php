<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmojiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        return DB::unprepared(
            file_get_contents(
                storage_path('sql/ref_emojis.sql'),
            )
        );
    }
}
