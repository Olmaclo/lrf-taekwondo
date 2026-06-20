<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->enum('weigh_in_status', ['passed', 'failed'])->nullable()->after('registration_status');
            $table->decimal('weigh_in_weight', 5, 2)->nullable()->after('weigh_in_status');
            $table->timestamp('weigh_in_at')->nullable()->after('weigh_in_weight');
            $table->foreignId('weigh_in_by')->nullable()->constrained('users')->nullOnDelete()->after('weigh_in_at');
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropForeign(['weigh_in_by']);
            $table->dropColumn(['weigh_in_status', 'weigh_in_weight', 'weigh_in_at', 'weigh_in_by']);
        });
    }
};
