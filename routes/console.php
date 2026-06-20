<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Chaque jeudi à 06h00 : vider tous les caches + OPcache après le backup Hostinger
// Le backup Hostinger s'exécute le mercredi soir et peut restaurer d'anciens fichiers.
// Ce nettoyage force Laravel à relire les fichiers présents sur le disque.
Schedule::call(function () {
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    Artisan::call('optimize:clear');
    file_put_contents(
        storage_path('logs/last_deploy.txt'),
        '[CRON] ' . now()->toDateTimeString() . " — cache vidé après backup Hostinger\n",
        FILE_APPEND
    );
})->weeklyOn(4, '06:00'); // 4 = jeudi

// Chaque jour à 02h00 : clôture automatique des événements dont la date de fin
// est dépassée (passage du statut à 'finished' → verrouille les écritures métier).
Schedule::command('events:auto-finish')->dailyAt('02:00');
