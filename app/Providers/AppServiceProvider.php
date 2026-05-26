<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Vite;
use Number;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
		// todo для прода .env.prod
        // app()->loadEnvironmentFrom()
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

		// Vite::prefetch(concurrency: 3);

		Number::useCurrency('RUB');
		Number::useLocale('ru_RU');
    }
}
