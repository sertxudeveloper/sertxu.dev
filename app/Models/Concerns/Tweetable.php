<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Spatie\Tags\Tag;

trait Tweetable
{
    abstract public function tweetUrl(): string;

    public function toTweet(): string
    {
        $tags = $this->tags
            ->map(function (Tag $tag): string {
                $name = $tag->getAttribute('name');

                return is_string($name) ? '#'.str_replace(' ', '', $name) : '';
            })
            ->filter(fn (string $tagName): bool => $tagName !== '')
            ->implode(' ');

        return '🔗 '.$this->title
            .PHP_EOL."\n"
            .PHP_EOL.$this->tweetUrl()
            .PHP_EOL.$tags;
    }
}
