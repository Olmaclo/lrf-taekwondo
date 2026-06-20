<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('permissions')
            ->where('name', 'moderate-live')->where('guard_name', 'web')->exists();

        if (! $exists) {
            DB::table('permissions')->insert([
                'name'       => 'moderate-live',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        DB::table('permissions')
            ->where('name', 'moderate-live')->where('guard_name', 'web')->delete();
    }
};
