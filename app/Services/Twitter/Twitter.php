<?php

declare(strict_types=1);

namespace App\Services\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;

final readonly class Twitter
{
    public function __construct(private TwitterOAuth $twitter) {}

    /**
     * Post a tweet with the given text.
     */
    public function tweet(string $text): ?array
    {
        if (! app()->environment('production')) {
            return null;
        }

        return (array) $this->twitter->post('tweets', ['text' => $text]);
    }
}
