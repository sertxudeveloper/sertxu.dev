<?php

declare(strict_types=1);

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\Twitter;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

final class TwitterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Twitter::class, function (): Twitter {
            $consumerKey = config('services.twitter.consumer_key');
            $consumerSecret = config('services.twitter.consumer_secret');
            $oauthToken = config('services.twitter.access_token');
            $oauthTokenSecret = config('services.twitter.access_token_secret');

            if (! is_string($consumerKey) || ! is_string($consumerSecret)) {
                throw new InvalidArgumentException('Twitter consumer credentials must be strings.');
            }

            if (($oauthToken !== null && ! is_string($oauthToken)) || ($oauthTokenSecret !== null && ! is_string($oauthTokenSecret))) {
                throw new InvalidArgumentException('Twitter access tokens must be strings or null.');
            }

            $connection = new TwitterOAuth(
                $consumerKey,
                $consumerSecret,
                $oauthToken,
                $oauthTokenSecret
            );

            return new Twitter($connection);
        });
    }
}
