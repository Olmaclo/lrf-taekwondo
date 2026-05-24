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

        // ── Technical user ─────────────────────────────────────────────────────
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

        // ── Multiple coaches from different clubs ──────────────────────────────
        $clubs = [
            'Dakar TK Club', 'Guédiawaye TK', 'Thiès Champions TK',
            'Saint-Louis TK Club', 'Ziguinchor TK', 'Kaolack TK Club',
            'Diourbel TK', 'Kolda TK', 'Matam TK', 'Tambacounda TK',
            'Louga TK Club', 'Fatick TK', 'Kaffrine TK', 'Sédhiou TK',
        ];

        $coaches = [];
        foreach ($clubs as $i => $club) {
            $slug = 'coach-' . ($i + 1) . '@test.com';
            $c = User::firstOrCreate(
                ['email' => $slug],
                [
                    'name'              => 'Coach ' . $club,
                    'club'              => $club,
                    'password'          => Hash::make('password'),
                    'is_validated'      => true,
                    'account_status'    => 'approved',
                    'email_verified_at' => now(),
                ]
            );
            $c->syncRoles(['coach']);
            $coaches[$club] = $c;
        }

        // ── Test event (withTrashed pour gérer le soft delete) ────────────────
        $event = Event::withTrashed()->where('slug', 'championnat-test-tirages-2025')->first();
        if ($event) {
            if ($event->trashed()) {
                $event->restore();
            }
            $event->update([
                'status'     => 'ongoing',
                'start_date' => now()->subDay()->format('Y-m-d'),
                'end_date'   => now()->addDay()->format('Y-m-d'),
            ]);
        } else {
            $event = Event::create([
                'slug'             => 'championnat-test-tirages-2025',
                'name'             => 'Championnat Test — Tirages 2025',
                'type'             => 'kyorugi',
                'status'           => 'ongoing',
                'start_date'       => now()->subDay()->format('Y-m-d'),
                'end_date'         => now()->addDay()->format('Y-m-d'),
                'location'         => 'Dakar Arena, Dakar',
                'registration_fee' => 5000,
                'description'      => 'Événement de test pour visualiser les brackets de tirage.',
            ]);
        }

        // ── Catégorie 1 : Senior Masculin -80kg — 45 athlètes (4 poules) ──────
        $seniorM80 = [
            ['Abdou',      'Diallo',     '2000-03-15', 79.5, 'Dakar TK Club'],
            ['Cheikh',     'Ba',         '1998-07-22', 78.0, 'Guédiawaye TK'],
            ['Ibrahima',   'Seck',       '1997-05-10', 79.0, 'Thiès Champions TK'],
            ['Moussa',     'Fall',       '2001-01-30', 80.0, 'Saint-Louis TK Club'],
            ['Ousmane',    'Ndiaye',     '1999-11-08', 77.5, 'Ziguinchor TK'],
            ['Lamine',     'Diop',       '2002-04-14', 78.5, 'Kaolack TK Club'],
            ['Mamadou',    'Gueye',      '2000-09-21', 79.8, 'Diourbel TK'],
            ['Pape',       'Thiam',      '1998-12-03', 76.5, 'Kolda TK'],
            ['Omar',       'Kane',       '1999-06-17', 80.0, 'Matam TK'],
            ['Babacar',    'Cisse',      '2001-08-29', 77.0, 'Tambacounda TK'],
            ['Modou',      'Toure',      '2000-02-14', 78.0, 'Louga TK Club'],
            ['Serigne',    'Diatta',     '1998-10-05', 79.2, 'Fatick TK'],
            ['Samba',      'Lo',         '2002-07-11', 78.8, 'Kaffrine TK'],
            ['Aliou',      'Mbaye',      '1997-04-25', 77.5, 'Sédhiou TK'],
            ['Boubacar',   'Faye',       '2001-11-18', 80.0, 'Dakar TK Club'],
            ['Malick',     'Sarr',       '1999-03-07', 76.0, 'Guédiawaye TK'],
            ['Mbaye',      'Diouf',      '2000-12-22', 79.0, 'Thiès Champions TK'],
            ['Ndiaga',     'Sy',         '1998-08-16', 78.5, 'Saint-Louis TK Club'],
            ['Oumar',      'Wade',       '2002-05-30', 77.0, 'Ziguinchor TK'],
            ['Papa',       'Ndour',      '2001-01-09', 80.0, 'Kaolack TK Club'],
            ['Saliou',     'Badji',      '1999-07-04', 78.0, 'Diourbel TK'],
            ['Seydou',     'Camara',     '2000-10-28', 79.5, 'Kolda TK'],
            ['Thierno',    'Deme',       '1997-02-13', 76.5, 'Matam TK'],
            ['Waly',       'Gomis',      '2001-09-01', 78.0, 'Tambacounda TK'],
            ['Abdoulaye',  'Keita',      '2000-04-19', 77.5, 'Louga TK Club'],
            ['Alioune',    'Mendy',      '1998-06-08', 79.0, 'Fatick TK'],
            ['Daouda',     'Niang',      '2002-11-24', 80.0, 'Kaffrine TK'],
            ['Famara',     'Sembene',    '1999-01-15', 78.5, 'Sédhiou TK'],
            ['Gora',       'Tine',       '2001-07-20', 77.0, 'Dakar TK Club'],
            ['Hamidou',    'Bodian',     '2000-03-31', 79.8, 'Guédiawaye TK'],
            ['Idrissa',    'Coulibaly',  '1998-09-12', 76.0, 'Thiès Champions TK'],
            ['Khadim',     'Dabo',       '2002-02-06', 78.0, 'Saint-Louis TK Club'],
            ['Landing',    'Diagne',     '1999-12-27', 79.5, 'Ziguinchor TK'],
            ['Pathé',      'Fofana',     '2001-05-14', 77.5, 'Kaolack TK Club'],
            ['Racine',     'Gassama',    '2000-08-03', 80.0, 'Diourbel TK'],
            ['Sidy',       'Kouyate',    '1997-11-17', 78.5, 'Kolda TK'],
            ['Tamsir',     'Manga',      '2002-04-01', 79.0, 'Matam TK'],
            ['Fallou',     'Tambédou',   '1999-06-26', 76.5, 'Tambacounda TK'],
            ['Gana',       'Dramé',      '2001-10-15', 78.0, 'Louga TK Club'],
            ['Alassane',   'Bah',        '2000-01-08', 77.5, 'Fatick TK'],
            ['Badara',     'Barry',      '1998-05-22', 79.5, 'Kaffrine TK'],
            ['Djibril',    'Coly',       '2002-08-17', 78.5, 'Sédhiou TK'],
            ['Baïla',      'Goudiaby',   '1999-03-11', 80.0, 'Dakar TK Club'],
            ['Maguette',   'Biaye',      '2001-12-04', 77.0, 'Guédiawaye TK'],
            ['Pap',        'Ndoye',      '2000-07-29', 79.0, 'Thiès Champions TK'],
        ];

        // ── Catégorie 2 : Junior Féminin -49kg — 7 athlètes (2 poules : 4+3) ──
        $juniorF49 = [
            ['Fatou',      'Ndiaye',     '2005-02-18', 48.5, 'Dakar TK Club'],
            ['Aminata',    'Diallo',     '2006-06-25', 49.0, 'Guédiawaye TK'],
            ['Mariama',    'Sow',        '2005-10-11', 48.0, 'Thiès Champions TK'],
            ['Rokhaya',    'Mbaye',      '2006-03-07', 47.5, 'Saint-Louis TK Club'],
            ['Ndéye',      'Faye',       '2005-08-22', 49.0, 'Ziguinchor TK'],
            ['Sokhna',     'Sarr',       '2006-11-30', 48.8, 'Kaolack TK Club'],
            ['Aissatou',   'Gueye',      '2005-04-14', 47.0, 'Diourbel TK'],
        ];

        // ── Catégorie 3 : Cadet Masculin -55kg — 8 athlètes (2 poules de 4) ───
        $cadetM55 = [
            ['Oumar',      'Kane',       '2008-01-05', 54.0, 'Dakar TK Club'],
            ['Babacar',    'Cisse',      '2009-04-17', 55.0, 'Guédiawaye TK'],
            ['Modou',      'Toure',      '2008-09-28', 53.5, 'Thiès Champions TK'],
            ['Serigne',    'Diatta',     '2009-02-14', 54.5, 'Saint-Louis TK Club'],
            ['Cheikh',     'Lo',         '2008-06-19', 54.0, 'Ziguinchor TK'],
            ['Ibou',       'Diallo',     '2009-11-03', 55.0, 'Kaolack TK Club'],
            ['Ngor',       'Fall',       '2008-07-22', 53.0, 'Diourbel TK'],
            ['Pape Samba', 'Ndiaye',     '2009-05-08', 54.8, 'Kolda TK'],
        ];

        $allGroups = [
            [
                'athletes'   => $seniorM80,
                'age'        => 'Senior',
                'gender'     => 'M',
                'weight'     => '-80kg',
                'weight_num' => 80.0,
                'birth_range'=> [1997, 2002],
            ],
            [
                'athletes'   => $juniorF49,
                'age'        => 'Junior',
                'gender'     => 'F',
                'weight'     => '-49kg',
                'weight_num' => 49.0,
                'birth_range'=> [2005, 2006],
            ],
            [
                'athletes'   => $cadetM55,
                'age'        => 'Cadet',
                'gender'     => 'M',
                'weight'     => '-55kg',
                'weight_num' => 55.0,
                'birth_range'=> [2008, 2009],
            ],
        ];

        Athlete::unguard();

        foreach ($allGroups as $group) {
            foreach ($group['athletes'] as [$firstName, $lastName, $dob, $weight, $club]) {
                $coach = $coaches[$club] ?? $coaches['Dakar TK Club'];

                Athlete::updateOrCreate(
                    [
                        'first_name' => $firstName,
                        'last_name'  => $lastName,
                        'event_id'   => $event->id,
                    ],
                    [
                        'birth_date'          => $dob,
                        'birth_place'         => 'Dakar',
                        'gender'              => $group['gender'],
                        'weight'              => $weight,
                        'age_category'        => $group['age'],
                        'weight_category'     => $group['weight'],
                        'club'                => $club,
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

        // ── Supprimer les anciens tirages pour forcer la régénération ──────────
        Draw::where('event_id', $event->id)->delete();

        // ── Générer les tirages ────────────────────────────────────────────────
        Auth::login($technical);
        $drawService = app(DrawGenerationService::class);

        foreach ($allGroups as $group) {
            try {
                $draw = $drawService->generate($event, $group['age'], $group['gender'], $group['weight']);
                $format = $draw->use_pools ? 'poules' : 'élim. directe';
                $this->command->info("✓ {$group['age']} {$group['gender']} {$group['weight']} — {$draw->total_athletes} athlètes ({$format})");
            } catch (\Throwable $e) {
                $this->command->warn("✗ {$group['age']} {$group['gender']} {$group['weight']} : " . $e->getMessage());
            }
        }

        Auth::logout();

        $this->command->newLine();
        $this->command->info('URL publique des tirages :');
        $this->command->info(url('/evenements/championnat-test-tirages-2025/tirages'));
    }
}
