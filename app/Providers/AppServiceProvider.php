<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Behind Coolify's TLS-terminating proxy the app sees plain HTTP, so
        // generated URLs (assets included) would be http:// and get blocked as
        // mixed content on the https site. Force https in production.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
