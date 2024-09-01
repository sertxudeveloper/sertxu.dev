<?php

namespace App\Models\Concerns;

use Spatie\Tags\Tag;

trait Tweetable
{
    public function toTweet(): string
    {
        $tags = $this->tags
            ->map(fn (Tag $tag) => $tag->name)
            ->map(fn (string $tagName) => '#'.str_replace(' ', '', $tagName))
            ->implode(' ');

        return '🔗 '.$this->title
            .PHP_EOL.$this->tweetUrl()
            .PHP_EOL.$tags;
    }

    abstract public function tweetUrl(): string;
}
