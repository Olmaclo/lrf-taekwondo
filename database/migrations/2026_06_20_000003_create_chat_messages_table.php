<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_session_id')->constrained()->cascadeOnDelete();
            $table->string('pseudo', 40);
            $table->text('message');
            $table->string('ip_hash', 64)->nullable();   // empreinte IP (jamais l'IP en clair)
            $table->boolean('is_deleted')->default(false); // modération douce
            $table->timestamps();

            $table->index(['live_session_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
