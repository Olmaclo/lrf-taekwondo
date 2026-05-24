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

        Artisan::call('optimize:clear');
        $output[] = 'optimize:clear — OK';

        Artisan::call('config:cache');
        $output[] = 'config:cache — OK';

        Artisan::call('route:cache');
        $output[] = 'route:cache — OK';

        Artisan::call('view:cache');
        $output[] = 'view:cache — OK';

        Artisan::call('migrate', ['--force' => true]);
        $output[] = 'migrate — OK';

        try {
            Artisan::call('db:seed', ['--class' => 'DrawTestSeeder', '--force' => true]);
            $output[] = 'DrawTestSeeder — OK';
        } catch (\Throwable $e) {
            $output[] = 'DrawTestSeeder — ERREUR: ' . $e->getMessage();
        }

        return response()->json([
            'success' => true,
            'deployed_at' => now()->toDateTimeString(),
            'steps' => $output,
        ]);
    }
}
