<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_bans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_session_id')->constrained()->cascadeOnDelete();
            $table->string('pseudo', 40)->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->foreignId('banned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['live_session_id', 'ip_hash']);
            $table->index(['live_session_id', 'pseudo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_bans');
    }
};
