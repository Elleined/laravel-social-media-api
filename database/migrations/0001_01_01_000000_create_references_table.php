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
        Schema::create('ref_emojis', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->timestamps();
            $table->string('name', length: 25)->unique();
        });

        Schema::create('ref_provider_types', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->timestamps();
            $table->string('name', length: 25)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_emojis');
        Schema::dropIfExists('ref_provider_types');
    }
};
