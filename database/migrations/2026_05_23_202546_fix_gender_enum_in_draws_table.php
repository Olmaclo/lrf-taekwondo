<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Original migration used ('Masculin', 'Feminin') but all application
        // code uses ('M', 'F') — matching the athletes table. Fix the enum.
        DB::statement("ALTER TABLE draws MODIFY COLUMN gender ENUM('M', 'F') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE draws MODIFY COLUMN gender ENUM('Masculin', 'Feminin') NOT NULL");
    }
};
