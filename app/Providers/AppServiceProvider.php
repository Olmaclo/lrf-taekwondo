<?php

namespace App\Providers;

use App\Models\Athlete;
use App\Models\Event;
use App\Services\DrawGenerationService;
use App\Services\WeightCategoryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WeightCategoryService::class);
        $this->app->singleton(DrawGenerationService::class);
    }

    public function boot(): void
    {
        // Strict mode in development — fail fast on lazy loads, mass-assignment, etc.
        Model::shouldBeStrict(! app()->isProduction());

        // Prevent N+1 silently in production (log instead of throw)
        if (app()->isProduction()) {
            Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
                logger()->warning("Lazy load violation: {$model}::{$relation}");
            });
        }

        // Security gates
        Gate::define('manage-athletes', fn ($user) => $user->isTechnical() || $user->isAdmin());
        Gate::define('manage-finance',  fn ($user) => $user->isFinancial()  || $user->isAdmin());
        Gate::define('manage-coaches',  fn ($user) => $user->isTechnical()  || $user->isAdmin());
    }
}
