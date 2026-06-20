<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\BlogPost;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ──────────────────────────────────────────────────────────────
        $roles = ['admin', 'technical', 'financial', 'coach'];
        foreach ($roles as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        if (! app()->environment('local', 'testing')) {
            $this->command->warn('Environnement de production détecté — données de démonstration ignorées.');
            return;
        }

        // ── Admin ──────────────────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@sotaemad.com'],
            [
                'name'              => 'Administrateur',
                'password'          => Hash::make('password'),
                'phone'             => '+221 77 000 00 00',
                'club'              => 'SOTAEMAD',
                'is_validated'      => true,
                'account_status'    => 'approved',
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['admin']);

        // ── Technical staff ────────────────────────────────────────────────────
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

        // ── Financial staff ────────────────────────────────────────────────────
        $financial = User::firstOrCreate(
            ['email' => 'finance@sotaemad.com'],
            [
                'name'              => 'Responsable Financier',
                'password'          => Hash::make('password'),
                'is_validated'      => true,
                'account_status'    => 'approved',
                'email_verified_at' => now(),
            ]
        );
        $financial->syncRoles(['financial']);

        // ── Demo coaches ───────────────────────────────────────────────────────
        $coachData = [
            ['name' => 'Moussa Diallo',    'email' => 'moussa@dakar-tk.com',   'club' => 'Dakar TK Club'],
            ['name' => 'Fatou Ndiaye',     'email' => 'fatou@lions-tk.com',    'club' => 'Lions TK'],
            ['name' => 'Ibrahima Seck',    'email' => 'ibrahima@pikine-tk.com','club' => 'Pikine TK'],
        ];

        $coaches = [];
        foreach ($coachData as $cd) {
            $coach = User::firstOrCreate(
                ['email' => $cd['email']],
                array_merge($cd, [
                    'password'          => Hash::make('password'),
                    'phone'             => '+221 77 ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                    'is_validated'      => true,
                    'account_status'    => 'approved',
                    'email_verified_at' => now(),
                ])
            );
            $coach->syncRoles(['coach']);
            $coaches[] = $coach;
        }

        // ── Events ─────────────────────────────────────────────────────────────
        $events = [
            [
                'name'             => 'Championnat National Seniors 2025',
                'slug'             => 'championnat-national-seniors-2025',
                'type'             => 'kyorugi',
                'status'           => 'open',
                'start_date'       => now()->addMonths(2)->format('Y-m-d'),
                'end_date'         => now()->addMonths(2)->addDays(1)->format('Y-m-d'),
                'location'         => 'Dakar Arena, Dakar',
                'registration_fee' => 5000,
                'description'      => 'Championnat national de Taekwondo — catégories seniors kyorugi.',
            ],
            [
                'name'             => 'Open International Dakar 2025',
                'slug'             => 'open-international-dakar-2025',
                'type'             => 'kyorugi',
                'status'           => 'upcoming',
                'start_date'       => now()->addMonths(4)->format('Y-m-d'),
                'end_date'         => now()->addMonths(4)->addDays(2)->format('Y-m-d'),
                'location'         => 'Complexe Sportif Léopold Sédar Senghor, Dakar',
                'registration_fee' => 10000,
                'description'      => 'Tournoi international ouvert à tous les clubs africains.',
            ],
            [
                'name'             => 'Interclubs Poomsae 2025',
                'slug'             => 'interclubs-poomsae-2025',
                'type'             => 'poomsae',
                'status'           => 'upcoming',
                'start_date'       => now()->addMonths(3)->format('Y-m-d'),
                'end_date'         => now()->addMonths(3)->format('Y-m-d'),
                'location'         => 'Salle omnisports de Thiaroye',
                'registration_fee' => 3000,
                'description'      => 'Compétition de formes — poomsae individuel et par équipe.',
            ],
        ];

        $createdEvents = [];
        foreach ($events as $ed) {
            $createdEvents[] = Event::firstOrCreate(['slug' => $ed['slug']], $ed);
        }

        // ── Demo athletes ──────────────────────────────────────────────────────
        if (Athlete::count() === 0) {
            $sampleAthletes = [
                // Coach 1 athletes
                ['first_name' => 'Abdou',    'last_name' => 'Diallo',   'birth_date' => '2000-03-15', 'gender' => 'M', 'weight' => 68.5, 'age_category' => 'Senior',  'weight_category' => '-68kg',  'club' => $coaches[0]->club, 'coach_id' => $coaches[0]->id, 'registration_status' => 'validated', 'payment_status' => 'validated', 'payment_amount' => 5000],
                ['first_name' => 'Cheikh',   'last_name' => 'Ba',       'birth_date' => '1998-07-22', 'gender' => 'M', 'weight' => 80.0, 'age_category' => 'Senior',  'weight_category' => '-80kg',  'club' => $coaches[0]->club, 'coach_id' => $coaches[0]->id, 'registration_status' => 'pending',   'payment_status' => 'unpaid',    'payment_amount' => null],
                ['first_name' => 'Mariama',  'last_name' => 'Diallo',   'birth_date' => '2002-11-30', 'gender' => 'F', 'weight' => 57.0, 'age_category' => 'Senior',  'weight_category' => '-57kg',  'club' => $coaches[0]->club, 'coach_id' => $coaches[0]->id, 'registration_status' => 'validated', 'payment_status' => 'paid',      'payment_amount' => 5000],
                // Coach 2 athletes
                ['first_name' => 'Oumar',    'last_name' => 'Ndiaye',   'birth_date' => '2005-01-10', 'gender' => 'M', 'weight' => 55.0, 'age_category' => 'Cadet',   'weight_category' => '-55kg',  'club' => $coaches[1]->club, 'coach_id' => $coaches[1]->id, 'registration_status' => 'validated', 'payment_status' => 'validated', 'payment_amount' => 5000],
                ['first_name' => 'Aminata',  'last_name' => 'Fall',     'birth_date' => '2006-04-18', 'gender' => 'F', 'weight' => 44.0, 'age_category' => 'Cadet',   'weight_category' => '-44kg',  'club' => $coaches[1]->club, 'coach_id' => $coaches[1]->id, 'registration_status' => 'pending',   'payment_status' => 'unpaid',    'payment_amount' => null],
                ['first_name' => 'Souleymane','last_name' => 'Cisse',   'birth_date' => '2001-09-05', 'gender' => 'M', 'weight' => 74.0, 'age_category' => 'Senior',  'weight_category' => '-74kg',  'club' => $coaches[1]->club, 'coach_id' => $coaches[1]->id, 'registration_status' => 'validated', 'payment_status' => 'temp_validated', 'payment_amount' => 5000],
                // Coach 3 athletes
                ['first_name' => 'Lamine',   'last_name' => 'Seck',     'birth_date' => '2007-06-20', 'gender' => 'M', 'weight' => 37.0, 'age_category' => 'Minime',  'weight_category' => '-37kg',  'club' => $coaches[2]->club, 'coach_id' => $coaches[2]->id, 'registration_status' => 'validated', 'payment_status' => 'validated', 'payment_amount' => 5000],
                ['first_name' => 'Rokhaya',  'last_name' => 'Mbaye',    'birth_date' => '2003-12-12', 'gender' => 'F', 'weight' => 62.0, 'age_category' => 'Junior',   'weight_category' => '-62kg',  'club' => $coaches[2]->club, 'coach_id' => $coaches[2]->id, 'registration_status' => 'validated', 'payment_status' => 'paid',      'payment_amount' => 5000],
                ['first_name' => 'Pape',     'last_name' => 'Gueye',    'birth_date' => '1999-08-25', 'gender' => 'M', 'weight' => 87.0, 'age_category' => 'Senior',  'weight_category' => '-87kg',  'club' => $coaches[2]->club, 'coach_id' => $coaches[2]->id, 'registration_status' => 'rejected',  'payment_status' => 'unpaid',    'payment_amount' => null],
            ];

            $event = $createdEvents[0];
            foreach ($sampleAthletes as $idx => $ad) {
                Athlete::create(array_merge($ad, [
                    'event_id'    => $event->id,
                    'nationality' => 'Sénégalais(e)',
                    'receipt_number' => ($ad['payment_status'] !== 'unpaid') ? Athlete::generateReceiptNumber() : null,
                ]));
            }
        }

        // ── Demo blog posts ────────────────────────────────────────────────────
        if (BlogPost::count() === 0) {
            $blogPosts = [
                [
                    'title'        => 'Résultats du Championnat National Seniors 2025',
                    'slug'         => 'resultats-championnat-national-seniors-2025',
                    'content'      => "Le Championnat National Seniors 2025 s'est tenu à Dakar Arena ce weekend, rassemblant plus de 120 athlètes venus de 18 clubs différents à travers le Sénégal.\n\nLa compétition a été marquée par de brillantes performances dans toutes les catégories de poids. Les clubs de Dakar TK, Lions TK et Pikine TK se sont particulièrement distingués avec de nombreuses médailles.\n\nDans la catégorie Senior masculin -68kg, Abdou Diallo du Dakar TK Club a remporté la médaille d'or après un parcours impressionnant sans aucune défaite.\n\nLa SOTAEMAD félicite tous les athlètes participants et remercie les coaches, arbitres et bénévoles qui ont contribué au succès de cet événement.\n\nLes prochaines compétitions sont déjà programmées. Restez connectés pour toutes les informations.",
                    'excerpt'      => 'Retour sur le Championnat National Seniors 2025 qui a réuni 120 athlètes de 18 clubs sénégalais à Dakar Arena.',
                    'status'       => 'published',
                    'published_at' => now()->subDays(3),
                    'author_id'    => $admin->id,
                ],
                [
                    'title'        => 'Ouverture des inscriptions — Open International Dakar 2025',
                    'slug'         => 'ouverture-inscriptions-open-international-dakar-2025',
                    'content'      => "La SOTAEMAD est heureuse d'annoncer l'ouverture officielle des inscriptions pour l'Open International Dakar 2025, prévu du " . now()->addMonths(4)->format('d/m/Y') . " au " . now()->addMonths(4)->addDays(2)->format('d/m/Y') . " au Complexe Sportif Léopold Sédar Senghor.\n\nCette compétition internationale accueillera des clubs de toute l'Afrique de l'Ouest. Les catégories ouvertes sont Benjamin, Minime, Cadet, Junior et Senior, disciplines Kyorugi et Poomsae.\n\nFrais d'inscription : 10 000 FCFA par athlète. Date limite d'inscription : 2 semaines avant la compétition.\n\nPour s'inscrire, rendez-vous sur la page d'inscription en ligne ou contactez votre coach qui soumettra les dossiers via la plateforme SOTAEMAD.\n\nNous avons hâte de vous accueillir pour cette belle fête du Taekwondo !",
                    'excerpt'      => "Inscriptions ouvertes pour l'Open International Dakar 2025 — tournoi africain ouvert à toutes les catégories.",
                    'status'       => 'published',
                    'published_at' => now()->subDays(10),
                    'author_id'    => $admin->id,
                ],
                [
                    'title'        => 'Formation arbitrage SOTAEMAD — Session 2025',
                    'slug'         => 'formation-arbitrage-sotaemad-session-2025',
                    'content'      => "Dans le cadre du développement du Taekwondo sénégalais, la SOTAEMAD organise une session de formation pour les arbitres et juges de compétition.\n\nCette formation, encadrée par des formateurs certifiés WT (World Taekwondo), couvrira les règles officielles du kyorugi et du poomsae, l'utilisation du système de protecteurs électroniques (PSS), la gestion des situations difficiles sur le tatami.\n\nDate : " . now()->addDays(14)->format('d M Y') . "\nLieu : Salle polyvalente, INSEP Dakar\nInscription obligatoire avant le " . now()->addDays(10)->format('d M Y') . "\n\nLes candidats doivent avoir au minimum une ceinture noire 1er dan et une expérience en compétition. Une attestation de formation sera délivrée à l'issue de la session.\n\nPour vous inscrire, envoyez vos informations à secretariat@sotaemad.sn",
                    'excerpt'      => 'Session de formation pour arbitres et juges de compétition, encadrée par des formateurs certifiés World Taekwondo.',
                    'status'       => 'published',
                    'published_at' => now()->subDays(18),
                    'author_id'    => $admin->id,
                ],
                [
                    'title'        => 'SOTAEMAD lance sa plateforme digitale de gestion',
                    'slug'         => 'sotaemad-lance-plateforme-digitale-gestion',
                    'content'      => "La Ligue Sénégalaise de Taekwondo (SOTAEMAD) franchit un cap majeur dans sa modernisation avec le lancement officiel de sa plateforme digitale de gestion des compétitions et des athlètes.\n\nCette plateforme permet désormais aux clubs et coaches d'inscrire leurs athlètes en ligne, de suivre le statut des inscriptions en temps réel, d'accéder aux résultats et classements, et de générer des reçus de paiement directement depuis leur espace personnel.\n\nLes athlètes peuvent vérifier leur statut d'inscription via la page de vérification publique, accessible sans connexion.\n\nLa SOTAEMAD tient à remercier tous ceux qui ont contribué au développement de cet outil qui va transformer la gestion du Taekwondo sénégalais.",
                    'excerpt'      => "La SOTAEMAD lance sa plateforme digitale pour la gestion des inscriptions, compétitions et classements du Taekwondo sénégalais.",
                    'status'       => 'published',
                    'published_at' => now()->subDays(30),
                    'author_id'    => $admin->id,
                ],
            ];

            foreach ($blogPosts as $post) {
                BlogPost::firstOrCreate(['slug' => $post['slug']], $post);
            }
        }

        $this->command->info('✓ Base de données initialisée avec succès.');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['admin',     'admin@sotaemad.com',    'password'],
                ['technical', 'technique@sotaemad.com', 'password'],
                ['financial', 'finance@sotaemad.com',  'password'],
                ['coach',     'moussa@dakar-tk.com',   'password'],
            ]
        );
    }
}
