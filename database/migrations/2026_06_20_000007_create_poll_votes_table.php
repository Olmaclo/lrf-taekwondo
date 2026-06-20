<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('option_index');
            $table->string('voter_hash', 64);              // empreinte du votant (anti double-vote)
            $table->timestamps();

            $table->unique(['poll_id', 'voter_hash']);     // un seul vote par votant et par sondage
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poll_votes');
    }
};
