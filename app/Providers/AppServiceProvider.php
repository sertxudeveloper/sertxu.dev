<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Throwable;

final class AppServiceProvider extends ServiceProvider
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
        } catch (Throwable) {
            return;
        }

        Model::shouldBeStrict(! app()->isProduction());
    }
}
