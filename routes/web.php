<?php

use App\Http\Controllers\AthleteController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DrawController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LiveSessionController;
use App\Http\Controllers\DeployController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeighInController;
use Illuminate\Support\Facades\Route;

// ── Sitemap ────────────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// ── Deploy webhook (post-FTP artisan commands, protégé par secret header) ─────
Route::post('/webhook/deploy', [\App\Http\Controllers\DeployController::class, 'hook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->middleware('throttle:10,1')
    ->name('webhook.deploy');

// ── Direct / Live (pages publiques) ──────────────────────────────────────────
Route::get('/direct',                [PublicController::class, 'lives'])->name('public.lives');
Route::get('/direct/{liveSession}',  [PublicController::class, 'live'])->name('public.live');

// ── Public draw partial routes (AJAX — no auth required) ──────────────────────
Route::get('/tirages/{draw}/partial', [DrawController::class, 'bracketPartial'])->name('draws.bracket-partial');
Route::get('/tirages/{draw}/status',  [DrawController::class, 'status'])->name('draws.status');
Route::get('/tirages/{draw}/pdf',     [DrawController::class, 'bracketPdf'])->name('draws.pdf')->middleware('throttle:10,1');

// ── Public ─────────────────────────────────────────────────────────────────────
Route::get('/',                        [PublicController::class, 'home'])->name('public.home');
Route::get('/evenements',              [PublicController::class, 'events'])->name('public.events');
Route::get('/evenements/{slug}',       [PublicController::class, 'eventDetail'])->name('public.event-detail');
Route::get('/evenements/{slug}/liste',            [PublicController::class, 'athleteList'])->name('public.athlete-list');
Route::get('/evenements/{slug}/liste/export/csv', [PublicController::class, 'athleteListCsv'])->name('public.athlete-list-csv')->middleware('throttle:10,1');
Route::get('/evenements/{slug}/tirages',          [PublicController::class, 'draws'])->name('public.draws');
Route::get('/classements',               [PublicController::class, 'rankings'])->name('public.rankings');
Route::get('/classements/export/csv',    [ExportController::class, 'rankingsCsv'])->name('public.rankings-csv')->middleware('throttle:10,1');
Route::get('/classements/export/pdf',    [ExportController::class, 'rankingsPdf'])->name('public.rankings-pdf')->middleware('throttle:10,1');
Route::get('/galerie',                 [PublicController::class, 'gallery'])->name('public.gallery');
Route::get('/actualites',              [PublicController::class, 'blog'])->name('public.blog');
Route::get('/actualites/{slug}',       [PublicController::class, 'blogPost'])->name('public.blog-post');
Route::get('/verifier-inscription',    [PublicController::class, 'verify'])->name('public.verify')->middleware('throttle:30,1');
Route::get('/contact',                 [PublicController::class, 'contact'])->name('public.contact');

// ── Inscription (coach validé uniquement) ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/inscription',  [PublicController::class, 'inscription'])->name('public.inscription');
    Route::post('/inscription', [PublicController::class, 'inscriptionStore'])->name('public.inscription.store');
});

// ── Legal pages ────────────────────────────────────────────────────────────────
Route::view('/politique-de-confidentialite', 'public.legal.privacy')->name('public.privacy');
Route::view('/conditions-dutilisation',      'public.legal.terms')->name('public.terms');
Route::view('/conformite-des-donnees',       'public.legal.data-compliance')->name('public.data-compliance');
Route::view('/propriete-intellectuelle',     'public.legal.intellectual-property')->name('public.intellectual-property');

// ── Auth ───────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',   [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login',  [AuthenticatedSessionController::class, 'store']);
    Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/forgot-password',        [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password',       [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password',        [NewPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');

// ── Authenticated ──────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard',           [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/technique', [DashboardController::class, 'technical'])->name('technical.dashboard');
    Route::get('/dashboard/coach',     [DashboardController::class, 'coach'])->name('coach.dashboard');
    Route::get('/dashboard/financier', [DashboardController::class, 'financial'])->name('financial.dashboard');

    // ── Events ────────────────────────────────────────────────────────────────
    Route::prefix('api/events')->name('api.events.')->group(function () {
        Route::get('/',           [EventController::class, 'index'])->name('index');
        Route::post('/',          [EventController::class, 'store'])->name('store');
        Route::get('/{event}',    [EventController::class, 'show'])->name('show');
        Route::put('/{event}',    [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
    });

    // ── Athletes ──────────────────────────────────────────────────────────────
    Route::prefix('api/athletes')->name('api.athletes.')->group(function () {
        Route::get('/',                          [AthleteController::class, 'index'])->name('index');
        Route::post('/',                         [AthleteController::class, 'store'])->name('store');
        Route::get('/categories-by-event',       [AthleteController::class, 'categoriesByEvent'])->name('categories');
        Route::get('/weight-categories',         [AthleteController::class, 'weightCategories'])->name('weight-categories');
        Route::post('/bulk-delete',              [AthleteController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::post('/bulk-validate',            [AthleteController::class, 'bulkValidate'])->name('bulk-validate');
        Route::post('/bulk-reject',              [AthleteController::class, 'bulkReject'])->name('bulk-reject');
        Route::post('/validate-by-club',         [AthleteController::class, 'validateByClub'])->name('validate-by-club');
        Route::post('/delete-by-club',           [AthleteController::class, 'destroyByClub'])->name('delete-by-club');
        Route::get('/{athlete}',                 [AthleteController::class, 'show'])->name('show');
        Route::put('/{athlete}',                 [AthleteController::class, 'update'])->name('update');
        Route::delete('/{athlete}',              [AthleteController::class, 'destroy'])->name('destroy');
        Route::post('/{athlete}/validate',       [AthleteController::class, 'validate'])->name('validate');
        Route::post('/{athlete}/reject',         [AthleteController::class, 'reject'])->name('reject');
    });

    // ── Draws ─────────────────────────────────────────────────────────────────
    Route::prefix('api/draws')->name('api.draws.')->group(function () {
        Route::post('/generate',            [DrawController::class, 'generate'])->name('generate');
        Route::get('/by-event',             [DrawController::class, 'byEvent'])->name('by-event');
        Route::get('/{draw}',               [DrawController::class, 'show'])->name('show');
        Route::delete('/{draw}',            [DrawController::class, 'destroy'])->name('destroy');
        Route::post('/{draw}/set-winner',   [DrawController::class, 'setWinner'])->name('set-winner');
        Route::post('/{draw}/reset-winner', [DrawController::class, 'resetWinner'])->name('reset-winner');
        Route::post('/{draw}/repair',       [DrawController::class, 'repair'])->name('repair');
    });

    // ── Direct / Live (gestion admin) ──────────────────────────────────────────
    Route::get('/dashboard/direct', [LiveSessionController::class, 'manage'])->name('live.manage');
    Route::prefix('api/live')->name('api.live.')->group(function () {
        Route::get('/',                 [LiveSessionController::class, 'index'])->name('index');
        Route::post('/',                [LiveSessionController::class, 'store'])->name('store');
        Route::put('/{liveSession}',    [LiveSessionController::class, 'update'])->name('update');
        Route::post('/{liveSession}/start', [LiveSessionController::class, 'start'])->name('start');
        Route::post('/{liveSession}/stop',  [LiveSessionController::class, 'stop'])->name('stop');
        Route::delete('/{liveSession}', [LiveSessionController::class, 'destroy'])->name('destroy');
    });

    // ── Coaches ───────────────────────────────────────────────────────────────
    Route::prefix('api/coaches')->name('api.coaches.')->group(function () {
        Route::get('/',                                 [CoachController::class, 'index'])->name('index');
        Route::get('/{coach}',                          [CoachController::class, 'show'])->name('show');
        Route::post('/{coach}/validate',                [CoachController::class, 'validate'])->name('validate');
        Route::post('/{coach}/reject',                  [CoachController::class, 'reject'])->name('reject');
        Route::post('/bulk-validate',                   [CoachController::class, 'bulkValidate'])->name('bulk-validate');
        Route::post('/bulk-reject',                     [CoachController::class, 'bulkReject'])->name('bulk-reject');
        Route::delete('/{coach}',                       [CoachController::class, 'destroy'])->name('destroy');
        Route::delete('/athletes/{athlete}/unregister', [CoachController::class, 'unregisterAthlete'])->name('unregister-athlete');
    });

    // ── Financial ─────────────────────────────────────────────────────────────
    Route::prefix('api/financial')->name('api.financial.')->group(function () {
        Route::post('/athletes/{athlete}/payment',       [FinancialController::class, 'markPayment'])->name('payment');
        Route::put('/athletes/{athlete}/payment',        [FinancialController::class, 'editPayment'])->name('edit-payment');
        Route::get('/athletes/{athlete}/payment',        [FinancialController::class, 'paymentDetails'])->name('payment-details');
        Route::post('/athletes/{athlete}/temp-validate', [FinancialController::class, 'tempValidate'])->name('temp-validate');
        Route::post('/bulk-temp-validate',               [FinancialController::class, 'bulkTempValidate'])->name('bulk-temp-validate');
        Route::post('/athletes/{athlete}/validate',      [FinancialController::class, 'definitiveValidate'])->name('definitive-validate');
        Route::post('/bulk-validate',                    [FinancialController::class, 'bulkDefinitiveValidate'])->name('bulk-definitive');
        Route::get('/athletes/{athlete}/receipt',        [FinancialController::class, 'generateReceipt'])->name('receipt');
    });

    // ── Rankings ──────────────────────────────────────────────────────────────
    Route::prefix('api/rankings')->name('api.rankings.')->group(function () {
        Route::get('/',                    [RankingController::class, 'index'])->name('index');
        Route::post('/',                   [RankingController::class, 'store'])->name('store');
        Route::post('/recalculate',        [RankingController::class, 'recalculate'])->name('recalculate');
        Route::delete('/{ranking}',        [RankingController::class, 'destroy'])->name('destroy');
    });

    // ── Gallery ───────────────────────────────────────────────────────────────
    Route::prefix('api/gallery')->name('api.gallery.')->group(function () {
        Route::get('/',                [GalleryController::class, 'index'])->name('index');
        Route::get('/stats',           [GalleryController::class, 'stats'])->name('stats');
        Route::post('/',               [GalleryController::class, 'store'])->name('store');
        Route::put('/{photo}',         [GalleryController::class, 'update'])->name('update');
        Route::delete('/{photo}',      [GalleryController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete',    [GalleryController::class, 'bulkDestroy'])->name('bulk-destroy');
    });

    // ── Blog ──────────────────────────────────────────────────────────────────
    Route::prefix('api/blog')->name('api.blog.')->group(function () {
        Route::get('/',                    [BlogController::class, 'index'])->name('index');
        Route::post('/',                   [BlogController::class, 'store'])->name('store');
        Route::get('/{blogPost}',          [BlogController::class, 'show'])->name('show');
        Route::post('/{blogPost}',         [BlogController::class, 'update'])->name('update');
        Route::delete('/{blogPost}',       [BlogController::class, 'destroy'])->name('destroy');
        Route::post('/{blogPost}/publish', [BlogController::class, 'publish'])->name('publish');
        Route::post('/{blogPost}/archive', [BlogController::class, 'archive'])->name('archive');
    });

    // ── Users ─────────────────────────────────────────────────────────────────
    Route::prefix('api/users')->name('api.users.')->group(function () {
        Route::get('/',                          [UserController::class, 'index'])->name('index');
        Route::post('/',                         [UserController::class, 'store'])->name('store');
        Route::get('/roles',                     [UserController::class, 'roles'])->name('roles');
        Route::put('/{user}/role',               [UserController::class, 'updateRole'])->name('role');
        Route::post('/{user}/toggle-validation', [UserController::class, 'toggleValidation'])->name('toggle-validation');
        Route::post('/{user}/send-reset',        [UserController::class, 'sendPasswordReset'])->name('send-reset');
        Route::delete('/{user}',                 [UserController::class, 'destroy'])->name('destroy');
    });

    // ── Pesée ─────────────────────────────────────────────────────────────────
    Route::get('/evenements/{slug}/pesee',              [WeighInController::class, 'index'])->name('weigh-in.index');
    Route::post('/api/athletes/{athlete}/weigh-in',     [WeighInController::class, 'declare'])->name('weigh-in.declare');
    Route::post('/api/athletes/{athlete}/weigh-in/reset', [WeighInController::class, 'reset'])->name('weigh-in.reset');

    // ── Exports (max 10 téléchargements/minute par utilisateur) ──────────────
    Route::middleware('throttle:10,1')->prefix('exports')->name('exports.')->group(function () {
        Route::get('/athletes/xlsx', [ExportController::class, 'athletes'])->name('athletes-xlsx');
        Route::get('/athletes/csv',  [ExportController::class, 'athletesCsv'])->name('athletes-csv');
        Route::get('/athletes/pdf',  [ExportController::class, 'athletesPdf'])->name('athletes-pdf');
    });

});
