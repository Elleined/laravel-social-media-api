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
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->timestamps();
            $table->softDeletes();
            $table->text('content');
            $table->string('attachment', length: 100)->nullable();
            $table->foreignUuid('author_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
