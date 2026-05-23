<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('club')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('club');
            $table->enum('gender', ['Masculin', 'Feminin'])->nullable()->after('birth_date');
            $table->string('country')->default('Sénégal')->after('gender');
            $table->boolean('is_validated')->default(false)->after('country');
            $table->enum('account_status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_validated');
            $table->string('avatar')->nullable()->after('account_status');
            $table->text('bio')->nullable()->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'club', 'birth_date', 'gender', 'country', 'is_validated', 'account_status', 'avatar', 'bio']);
        });
    }
};
