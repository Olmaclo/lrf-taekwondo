<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class WordPressUsersSeeder extends Seeder
{
    // Temporary password for all migrated accounts. Users should reset via email.
    const TEMP_PASSWORD = 'Sotaemad@2026';

    public function run(): void
    {
        // Ensure roles exist
        foreach (['admin', 'technical', 'financial', 'coach'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $users = [
            // ── Admins ──────────────────────────────────────────────────────────
            [
                'name'  => 'Admin',
                'email' => 'elitedev9@gmail.com',
                'role'  => 'admin',
                'phone' => null,
                'club'  => null,
            ],
            [
                'name'  => 'Seydina Mané',
                'email' => 'alioumane1602@gmail.com',
                'role'  => 'admin',
                'phone' => null,
                'club'  => null,
            ],
            [
                'name'  => 'Daouda Cissé',
                'email' => 'daouda6c@gmail.com',
                'role'  => 'admin',
                'phone' => null,
                'club'  => null,
            ],

            // ── Technical directors ──────────────────────────────────────────────
            [
                'name'  => 'Yoro Alassane Ndiaye',
                'email' => 'yoro@gmail.com',
                'role'  => 'technical',
                'phone' => null,
                'club'  => null,
            ],
            [
                'name'  => 'Ansou Sambou',
                'email' => 'sambouansoumana3@gmail.com',
                'role'  => 'technical',
                'phone' => null,
                'club'  => null,
            ],
            [
                'name'  => 'Yoro Alassane Ndiaye',
                'email' => 'ndiayeyoroassane@gmail.com',
                'role'  => 'technical',
                'phone' => null,
                'club'  => null,
            ],

            // ── Financial ────────────────────────────────────────────────────────
            [
                'name'  => 'Daouda Cissé',
                'email' => 'daouda@gmail.com',
                'role'  => 'financial',
                'phone' => null,
                'club'  => null,
            ],
            [
                'name'  => 'Mamadou Diedhiou',
                'email' => 'Boyjumo87@hotmail.com',
                'role'  => 'financial',
                'phone' => null,
                'club'  => null,
            ],
            [
                'name'  => 'Compte Multirôle',
                'email' => 'liguedefatick@sotaemad.com',
                'role'  => 'financial',
                'phone' => null,
                'club'  => null,
            ],

            // ── Coaches ──────────────────────────────────────────────────────────
            [
                'name'  => 'Aliou Mané',
                'email' => 'aliou4242022@gmail.com',
                'role'  => 'coach',
                'phone' => '776580465',
                'club'  => 'Sotaemad',
            ],
            [
                'name'  => 'Talla Samb',
                'email' => 'ts779361249@gmail.com',
                'role'  => 'coach',
                'phone' => '774156931',
                'club'  => 'Team Ndiam Taekwondo Club',
            ],
            [
                'name'  => 'Ababacar Ndiaye',
                'email' => 'ndiayechaufroid@gmail.com',
                'role'  => 'coach',
                'phone' => '775420622',
                'club'  => 'Sine Taekwondo Club',
            ],
            [
                'name'  => 'Modou Diop',
                'email' => 'touredamebravo92@gmail.com',
                'role'  => 'coach',
                'phone' => '777397498',
                'club'  => 'Colobane taekwondo',
            ],
            [
                'name'  => 'Ababacar Ndiaye',
                'email' => 'ndiayechaudfroid@yahoo.fr',
                'role'  => 'coach',
                'phone' => '775420622',
                'club'  => 'Sine Taekwondo Club',
            ],
            [
                'name'  => 'Cheick Faye',
                'email' => '783901830ba@gmail.com',
                'role'  => 'coach',
                'phone' => '783901830',
                'club'  => 'TeamBa taekwondo',
            ],
            [
                'name'  => 'Ibrahima Sarr',
                'email' => 'sarribra490@gmail.com',
                'role'  => 'coach',
                'phone' => '784904778',
                'club'  => 'DRAGON TEAM',
            ],
            [
                'name'  => 'Idrissa Ndior',
                'email' => 'ndioidrissa2003@gmail.com',
                'role'  => 'coach',
                'phone' => '771993124',
                'club'  => 'Djogomossane Taekwondo Club',
            ],
            [
                'name'  => 'Bounama Coulibaly',
                'email' => 'Coulibalybounama66@gmail.com',
                'role'  => 'coach',
                'phone' => '779088522',
                'club'  => 'Nasroudine tkd',
            ],
            [
                'name'  => 'Babacar Fall',
                'email' => '657senghor@gmail.com',
                'role'  => 'coach',
                'phone' => '783669137',
                'club'  => 'Karang Taekwondo club',
            ],
            [
                'name'  => 'Sotaemad Club',
                'email' => 'alioubafou@gmail.com',
                'role'  => 'coach',
                'phone' => null,
                'club'  => 'Sotaemad',
            ],
        ];

        $imported = 0;
        $skipped  = 0;

        foreach ($users as $data) {
            if (User::where('email', $data['email'])->exists()) {
                $skipped++;
                continue;
            }

            $user = User::create([
                'name'              => $data['name'],
                'email'             => strtolower($data['email']),
                'password'          => Hash::make(self::TEMP_PASSWORD),
                'phone'             => $data['phone'],
                'club'              => $data['club'],
                'is_validated'      => true,
                'account_status'    => 'approved',
                'email_verified_at' => now(),
            ]);

            $user->syncRoles([$data['role']]);
            $imported++;
        }

        $this->command->info("✓ WordPress migration: {$imported} comptes importés, {$skipped} ignorés (email déjà existant).");
        $this->command->info('  Mot de passe temporaire: ' . self::TEMP_PASSWORD);
        $this->command->warn('  ⚠ Demandez aux utilisateurs de changer leur mot de passe à la première connexion.');
    }
}
