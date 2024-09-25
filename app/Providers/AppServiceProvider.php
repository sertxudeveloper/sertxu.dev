<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        // Don't kill the app if the database hasn't been created.
        try {
            DB::connection('sqlite')->statement('PRAGMA synchronous = OFF;');
        } catch (\Throwable $throwable) {
            return;
        }

        Model::shouldBeStrict(! app()->isProduction());
    }
}
