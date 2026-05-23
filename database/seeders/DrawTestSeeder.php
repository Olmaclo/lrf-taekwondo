<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Draw;
use App\Models\Event;
use App\Models\User;
use App\Services\DrawGenerationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DrawTestSeeder extends Seeder
{
    public function run(): void
    {
        // ── Ensure roles exist ─────────────────────────────────────────────────
        foreach (['admin', 'technical', 'financial', 'coach'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ── Technical user (needed for draw generation auth) ──────────────────
        $technical = User::firstOrCreate(
            ['email' => 'technique@sotaemad.com'],
            [
                'name'              => 'Responsable Technique',
                'password'          => Hash::make('password'),
                'is_validated'      => true,
                'account_status'    => 'approved',
                'email_verified_at' => now(),
            ]
        );
        $technical->syncRoles(['technical']);

        // ── Demo coach ─────────────────────────────────────────────────────────
        $coach = User::firstOrCreate(
            ['email' => 'test-coach@dakar-tk.com'],
            [
                'name'              => 'Coach Test',
                'club'              => 'Dakar TK Club',
                'password'          => Hash::make('password'),
                'is_validated'      => true,
                'account_status'    => 'approved',
                'email_verified_at' => now(),
            ]
        );
        $coach->syncRoles(['coach']);

        // ── Test event ─────────────────────────────────────────────────────────
        $event = Event::firstOrCreate(
            ['slug' => 'championnat-test-tirages-2025'],
            [
                'name'             => 'Championnat Test — Tirages 2025',
                'type'             => 'kyorugi',
                'status'           => 'ongoing',
                'start_date'       => now()->subDay()->format('Y-m-d'),
                'end_date'         => now()->addDay()->format('Y-m-d'),
                'location'         => 'Dakar Arena, Dakar',
                'registration_fee' => 5000,
                'description'      => 'Événement de test pour visualiser les brackets de tirage.',
            ]
        );

        // ── Athlete data grouped by category ──────────────────────────────────
        // Group A: Senior Male -68kg — 8 athletes → direct elimination (3 rounds)
        $seniorM68 = [
            ['Abdou',      'Diallo',   '2000-03-15', 'M', 68.0, 'Senior', '-68kg'],
            ['Cheikh',     'Ba',       '1998-07-22', 'M', 67.5, 'Senior', '-68kg'],
            ['Ibrahima',   'Seck',     '1997-05-10', 'M', 66.0, 'Senior', '-68kg'],
            ['Moussa',     'Fall',     '2001-01-30', 'M', 68.5, 'Senior', '-68kg'],
            ['Ousmane',    'Ndiaye',   '1999-11-08', 'M', 67.0, 'Senior', '-68kg'],
            ['Lamine',     'Diop',     '2002-04-14', 'M', 65.5, 'Senior', '-68kg'],
            ['Mamadou',    'Gueye',    '2000-09-21', 'M', 67.8, 'Senior', '-68kg'],
            ['Pape',       'Thiam',    '1998-12-03', 'M', 66.5, 'Senior', '-68kg'],
        ];

        // Group B: Junior Female -57kg — 6 athletes → pool format (2 pools of 3)
        $juniorF57 = [
            ['Fatou',      'Ndiaye',   '2004-02-18', 'F', 56.0, 'Junior', '-57kg'],
            ['Aminata',    'Diallo',   '2005-06-25', 'F', 57.0, 'Junior', '-57kg'],
            ['Mariama',    'Sow',      '2004-10-11', 'F', 55.5, 'Junior', '-57kg'],
            ['Rokhaya',    'Mbaye',    '2005-03-07', 'F', 56.5, 'Junior', '-57kg'],
            ['Ndéye',      'Faye',     '2004-08-22', 'F', 57.0, 'Junior', '-57kg'],
            ['Sokhna',     'Sarr',     '2005-11-30', 'F', 55.0, 'Junior', '-57kg'],
        ];

        // Group C: Cadet Male -55kg — 5 athletes → direct elimination with 1 bye
        $cadetM55 = [
            ['Oumar',      'Kane',     '2007-01-05', 'M', 54.0, 'Cadet', '-55kg'],
            ['Babacar',    'Cisse',    '2008-04-17', 'M', 55.0, 'Cadet', '-55kg'],
            ['Modou',      'Toure',    '2007-09-28', 'M', 53.5, 'Cadet', '-55kg'],
            ['Serigne',    'Diatta',   '2008-02-14', 'M', 54.5, 'Cadet', '-55kg'],
            ['Cheikh',     'Lo',       '2007-06-19', 'M', 54.0, 'Cadet', '-55kg'],
        ];

        $allGroups = [
            ['athletes' => $seniorM68, 'age' => 'Senior', 'gender' => 'M', 'weight' => '-68kg'],
            ['athletes' => $juniorF57, 'age' => 'Junior', 'gender' => 'F', 'weight' => '-57kg'],
            ['athletes' => $cadetM55,  'age' => 'Cadet',  'gender' => 'M', 'weight' => '-55kg'],
        ];

        // Unguard to allow mass-assigning protected status fields in seeder context
        Athlete::unguard();

        foreach ($allGroups as $group) {
            foreach ($group['athletes'] as [$firstName, $lastName, $dob, $gender, $weight, $age, $weightCat]) {
                Athlete::firstOrCreate(
                    [
                        'first_name' => $firstName,
                        'last_name'  => $lastName,
                        'event_id'   => $event->id,
                    ],
                    [
                        'birth_date'          => $dob,
                        'birth_place'         => 'Dakar',
                        'gender'              => $gender,
                        'weight'              => $weight,
                        'age_category'        => $age,
                        'weight_category'     => $weightCat,
                        'club'                => $coach->club,
                        'nationality'         => 'Sénégalais(e)',
                        'license_number'      => 'SEN-' . rand(10000, 99999),
                        'coach_id'            => $coach->id,
                        'registration_status' => 'validated',
                        'payment_status'      => 'validated',
                        'payment_amount'      => 5000,
                        'created_by'          => $technical->id,
                    ]
                );
            }
        }

        Athlete::reguard();

        // ── Generate draws ─────────────────────────────────────────────────────
        Auth::login($technical);

        $drawService = app(DrawGenerationService::class);

        foreach ($allGroups as $group) {
            try {
                $draw = $drawService->generate($event, $group['age'], $group['gender'], $group['weight']);
                $this->command->info("✓ Tirage généré : {$group['age']} {$group['gender']} {$group['weight']} ({$draw->total_athletes} athlètes, " . ($draw->use_pools ? 'poules' : 'élim. directe') . ')');
            } catch (\Throwable $e) {
                $this->command->warn("✗ Échec tirage {$group['age']} {$group['gender']} {$group['weight']} : " . $e->getMessage());
            }
        }

        Auth::logout();

        $this->command->info('');
        $this->command->info('URL publique des tirages :');
        $this->command->info(url('/evenements/championnat-test-tirages-2025/tirages'));
    }
}
