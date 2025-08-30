<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\InvoiceService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(InvoiceService::class, fn($app) => new InvoiceService($app['view']));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
