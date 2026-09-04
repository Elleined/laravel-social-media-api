<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_reactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->timestamp('created_at')->useCurrent();
            $table->foreignUuid('emoji_id')->constrained('ref_emojis');

            $table->foreignUuid('reactor_id')->constrained('users');
            $table->foreignUuid('post_id')->constrained('posts');

            $table->unique(['reactor_id', 'post_id']);
        });

        Schema::create('comment_reactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->timestamp('created_at')->useCurrent();
            $table->foreignUuid('emoji_id')->constrained('ref_emojis');

            $table->foreignUuid('reactor_id')->constrained('users');
            $table->foreignUuid('comment_id')->constrained('comments');

            $table->unique(['reactor_id', 'comment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_reactions');
        Schema::dropIfExists('comment_reactions');
    }
};
