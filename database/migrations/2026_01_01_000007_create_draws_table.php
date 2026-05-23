<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('draws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();

            // Category info
            $table->string('category');            // "Senior M -54kg" (display)
            $table->enum('age_category', ['Benjamin', 'Minime', 'Cadet', 'Junior', 'Senior']);
            $table->enum('gender', ['Masculin', 'Feminin']);
            $table->string('weight_category');     // "-54kg"

            // Draw data
            $table->integer('total_athletes')->default(0);
            $table->boolean('use_pools')->default(false);
            $table->json('matches')->nullable();   // array of match objects
            $table->json('pools')->nullable();     // array of pool objects (if use_pools)

            // Meta
            $table->foreignId('generated_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();

            $table->unique(['event_id', 'age_category', 'gender', 'weight_category']);
            $table->index(['event_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('draws');
    }
};
