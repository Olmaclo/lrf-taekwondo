<h1 align="center">🥋 SOTAEMAD — Plateforme de la Ligue de Taekwondo de Fatick</h1>

<p align="center">
  <strong>Gestion complète des compétitions de Taekwondo : inscriptions, pesées, tirages, paiements et classements.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Tests-157%20passing-success?logo=pest&logoColor=white" alt="Tests">
  <img src="https://img.shields.io/badge/Licence-MIT-blue" alt="Licence MIT">
</p>

---

## 📖 À propos

**SOTAEMAD** est la plateforme web officielle de la **Ligue Régionale de Taekwondo de Fatick** (Sénégal), disponible sur [lrftaekwondo.com](https://lrftaekwondo.com).

Elle couvre tout le cycle de vie d'une compétition de Taekwondo :

- inscription des athlètes par les clubs (coaches),
- validation technique et calcul automatique des catégories d'âge/poids,
- gestion financière (paiements, reçus, validations),
- contrôle des pesées le jour de la compétition,
- génération des tirages (brackets) et suivi des résultats,
- classements par saison,
- vitrine publique (événements, résultats, galerie, actualités).

---

## ✨ Fonctionnalités

### 🗓️ Événements
- Cycle de vie complet : *à venir → inscriptions ouvertes → fermées → en cours → terminé / annulé*.
- **Clôture automatique** : un événement dont la date de fin est passée bascule en « terminé » chaque nuit (tâche planifiée).
- **Verrouillage** : un événement terminé fige son historique — plus aucune écriture financière possible (les techniciens conservent un droit de correction).

### 🥋 Athlètes
- Inscription par les coaches (limitée à leurs propres athlètes, uniquement quand les inscriptions sont ouvertes).
- Calcul automatique de la **catégorie d'âge** (Minime, Cadet, Junior, Senior) et de la **catégorie de poids**.
- Validation / rejet à l'unité, en masse, ou par club.
- Protection anti-doublon (même nom + même événement).

### ⚖️ Pesées
- Déclaration du poids réel le jour J.
- Détermination automatique *réussi / hors-catégorie* selon la fourchette de la catégorie.

### 🏆 Tirages (brackets)
- Génération en **élimination directe** ou en **poules** selon le nombre d'athlètes.
- Gestion des BYE, propagation automatique des vainqueurs, match pour la 3ᵉ place.
- Export **PDF** et affichage public en temps réel.

### 💰 Finances
- Enregistrement des paiements, validation temporaire et définitive.
- Génération de **reçus PDF**.
- Journal financier (traçabilité de chaque action).

### 📊 Classements
- Agrégation par saison et par catégorie.
- Export CSV / PDF public.

### 🌐 Espace public
Accueil, calendrier des événements, listes d'athlètes, tirages, classements, galerie photo, blog d'actualités et vérification d'inscription.

---

## 👥 Rôles utilisateurs

| Rôle | Périmètre |
|------|-----------|
| **Admin** | Accès complet (cumule technique + financier) |
| **Technique** | Athlètes, pesées, tirages, événements, classements |
| **Financier** | Paiements, reçus, validations financières |
| **Coach** | Inscription et gestion de ses propres athlètes |

La gestion des rôles et permissions s'appuie sur [spatie/laravel-permission](https://spatie.be/docs/laravel-permission).

---

## 🛠️ Stack technique

- **Backend** : [Laravel 12](https://laravel.com) · PHP 8.3
- **Frontend** : Blade · [Livewire 3](https://livewire.laravel.com) / Alpine.js (tableaux de bord en SPA)
- **Base de données** : MySQL (production) · SQLite en mémoire (tests)
- **Auth & rôles** : Laravel Sanctum · spatie/laravel-permission
- **PDF** : barryvdh/laravel-dompdf (reçus, brackets)
- **Export Excel/CSV** : maatwebsite/excel
- **Tests** : [Pest 3](https://pestphp.com) — 157 tests / 370 assertions

---

## 🚀 Installation locale

### Prérequis
- PHP **8.3+**
- Composer
- Node.js & npm
- MySQL (ou SQLite pour un démarrage rapide)

### Mise en route

```bash
# 1. Cloner le dépôt
git clone git@github.com:Olmaclo/lrf-taekwondo.git
cd lrf-taekwondo

# 2. Installation complète (dépendances, .env, clé, migrations + seed, assets)
composer setup

# 3. Lancer l'environnement de développement
#    (serveur + worker de queue + logs + Vite, en parallèle)
composer dev
```

L'application est alors disponible sur `http://localhost:8000`.

> 💡 Le script `composer setup` copie `.env.example` → `.env`, génère la clé applicative, exécute les migrations avec données de démonstration, puis compile les assets.

---

## 🧪 Tests

La suite est écrite avec **Pest** et tourne sur une base SQLite en mémoire (aucune base externe requise).

```bash
composer test
# ou directement
php artisan test
```

Couverture actuelle : **157 tests**, **370 assertions** — contrôleurs (athlètes, coaches, événements, finances, pesées, tirages), cycle de vie des événements, génération de brackets, catégories de poids et notifications.

---

## 🔒 Sécurité

- En-têtes de sécurité HTTP renforcés (CSP, HSTS, anti-clickjacking) via un middleware dédié.
- Protection contre l'assignation de masse : les champs sensibles (statuts, paiements) ne sont modifiables que par les contrôleurs autorisés.
- Contrôle d'accès strict par rôle sur chaque endpoint.
- Données de démonstration désactivées hors environnement local / test.
- Audit de sécurité de type OWASP réalisé sur l'ensemble du code.

---

## 📦 Déploiement

Le déploiement s'effectue par transfert des fichiers vers l'hébergement, suivi d'un **webhook protégé par secret** qui :

1. réinitialise l'OPcache PHP,
2. vide les caches Laravel (`optimize:clear`),
3. peut déclencher des commandes de maintenance whitelistées (ex. clôture automatique des événements).

Les tâches récurrentes (clôture quotidienne des événements terminés, nettoyage hebdomadaire des caches) sont gérées par le **planificateur Laravel** (`schedule:run`) via une tâche cron côté serveur.

> ⚠️ Les secrets (identifiants FTP, secret de déploiement, clés d'API) ne sont **jamais** versionnés — ils résident uniquement dans le `.env` du serveur.

---

## 📁 Structure du projet

```
app/
├── Console/Commands/      # Commandes artisan (ex. events:auto-finish)
├── Http/Controllers/      # Logique métier (athlètes, finances, tirages…)
├── Http/Middleware/       # Sécurité (en-têtes HTTP)
├── Mail/                  # E-mails (validations coach/athlète)
├── Models/                # Eloquent (Event, Athlete, Draw, User…)
└── Services/              # Domaine métier (tirages, catégories de poids)
database/
├── factories/ · migrations/ · seeders/
resources/views/           # Vues Blade (public + tableaux de bord)
routes/                    # web.php · console.php (planificateur)
tests/                     # Suites Pest (Feature + Unit)
```

---

## 📄 Licence

Projet développé pour la **Ligue Régionale de Taekwondo de Fatick**.
Code source publié sous licence [MIT](https://opensource.org/licenses/MIT).
