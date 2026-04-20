<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\CurrencyService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $currencyService = app(CurrencyService::class);

            $view->with('currentCurrency', $currencyService->getCurrentCurrency());
            $view->with('allowedCurrencies', $currencyService->getAllowedCurrencies());
        });
    }
}
