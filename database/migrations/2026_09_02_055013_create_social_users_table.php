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
        Schema::create('social_users', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->timestamp('created_at')->useCurrent();

            $table->foreignUuid('provider_type_id')->constrained('ref_provider_types');
            $table->string('provider_id', length: 50)->unique();

            $table->foreignUuid('user_id')->constrained('users');

            $table->unique(['provider_type_id', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_users');
    }
};
