<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('season', 4);            // "2026"
            $table->string('category');             // "Senior|M|-68kg"
            $table->tinyInteger('position')->unsigned()->nullable(); // 1,2,3…
            $table->smallInteger('points')->unsigned()->default(0);
            $table->tinyInteger('wins')->unsigned()->default(0);
            $table->tinyInteger('losses')->unsigned()->default(0);
            $table->timestamps();

            $table->unique(['athlete_id', 'event_id', 'category']);
            $table->index(['season', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rankings');
    }
};
