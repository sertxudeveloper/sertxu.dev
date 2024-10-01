<?php

declare(strict_types=1);

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\Twitter;
use Illuminate\Support\ServiceProvider;

final class TwitterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Twitter::class, function (): Twitter {
            $connection = new TwitterOAuth(
                config('services.twitter.consumer_key'),
                config('services.twitter.consumer_secret'),
                config('services.twitter.access_token'),
                config('services.twitter.access_token_secret')
            );

            return new Twitter($connection);
        });
    }
}
