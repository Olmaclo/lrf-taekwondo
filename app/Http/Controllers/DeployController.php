<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    public function hook(Request $request)
    {
        $secret = config('app.deploy_secret');

        if (empty($secret) || $request->header('X-Deploy-Secret') !== $secret) {
            abort(403, 'Forbidden');
        }

        $output = [];

        // 1. Vider l'OPcache PHP pour que les nouveaux fichiers uploadés soient lus
        if (function_exists('opcache_reset')) {
            opcache_reset();
            $output[] = 'opcache_reset — OK';
        }

        // 2. Vider TOUS les caches Laravel (config, routes, vues, app)
        //    On ne re-cache JAMAIS ici : sur hébergement partagé, le re-cache
        //    "gèle" l'état au moment de l'appel, ce qui peut capturer des fichiers
        //    non encore uploadés et masquer les mises à jour suivantes.
        Artisan::call('optimize:clear');
        $output[] = 'optimize:clear — OK';

        // 3. Commande de maintenance optionnelle — whitelist STRICTE.
        //    Permet de déclencher une commande artisan sûre à distance sur un
        //    hébergement sans accès SSH. Chaque alias est mappé vers un appel
        //    artisan contrôlé (nom + paramètres) ; toute valeur hors liste est ignorée.
        $allowed = [
            'events:auto-finish' => ['events:auto-finish', []],
            // Traite la file d'attente (emails) puis s'arrête quand elle est vide.
            'queue:flush'        => ['queue:work', ['--stop-when-empty' => true, '--max-time' => 30, '--tries' => 3]],
            // Applique les migrations en attente (le déploiement Git ne migre pas).
            'migrate'            => ['migrate', ['--force' => true]],
        ];
        $command = (string) ($request->input('command') ?? '');
        if ($command !== '' && isset($allowed[$command])) {
            [$cmd, $params] = $allowed[$command];
            Artisan::call($cmd, $params);
            $output[] = "{$command} — " . trim(Artisan::output());
        }

        // 4. Écrire un fichier de timestamp pour tracer chaque déploiement
        file_put_contents(
            storage_path('logs/last_deploy.txt'),
            now()->toDateTimeString() . "\n",
            FILE_APPEND
        );
        $output[] = 'timestamp — OK';

        return response()->json([
            'success'     => true,
            'deployed_at' => now()->toDateTimeString(),
            'steps'       => $output,
        ]);
    }

}
