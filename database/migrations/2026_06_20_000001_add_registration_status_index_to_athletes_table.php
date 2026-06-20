<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index simple sur registration_status : couvre les requêtes globales
     * (stats accueil, comptages) qui filtrent sur ce statut sans event_id.
     * Le composite (event_id, registration_status) ne sert pas ces cas.
     */
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->index('registration_status');
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropIndex(['registration_status']);
        });
    }
};
