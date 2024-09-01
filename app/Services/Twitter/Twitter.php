<?php

namespace App\Services\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter
{
    protected TwitterOAuth $twitter;

    public function __construct(TwitterOAuth $twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * Post a tweet with the given text.
     *
     * @param string $text
     * @return array|null
     */
    public function tweet(string $text): ?array
    {
        if (! app()->environment('production')) {
            return null;
        }

        return (array) $this->twitter->post('tweets', ['text' => $text]);
    }
}
