<?php

namespace App\Providers;

use App\Http\Custom\CustomRateLimiter;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RateLimiter::class, CustomRateLimiter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
