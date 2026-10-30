<?php

namespace App\Providers;

use App\Models\LoanService;
use App\Models\SavingPlan;
use App\Models\SocialLink;
use App\Services\ContentCache;
use App\Services\SettingsService;
use App\Services\UiTextService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once app_path('Support/helpers.php');

        $this->app->singleton(SettingsService::class);
        $this->app->singleton(UiTextService::class);
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.site');
        Paginator::defaultSimpleView('vendor.pagination.site');

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Shared data for the public layout (header mega-menu + footer), cached
        // and invalidated automatically whenever the admin changes content.
        View::composer(['layouts.site', 'partials.site.*'], function ($view) {
            static $shared = null;

            if ($shared === null) {
                $shared = ContentCache::remember('layout', fn () => [
                    'navPlans' => SavingPlan::active()->orderBy('sort_order')->get(['id', 'slug', 'name_en', 'name_ne']),
                    'navLoans' => LoanService::active()->orderBy('sort_order')->get(['id', 'slug', 'name_en', 'name_ne']),
                    'socialLinks' => SocialLink::active()->orderBy('sort_order')->get(),
                ]);
            }

            $view->with($shared);
        });
    }
}
