<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Spatie\Tags\Tag;

trait Threadable
{
    abstract public function threadsUrl(): string;

    public function toThreads(): string
    {
        $tags = $this->tags
            ->map(fn (Tag $tag) => $tag->name)
            ->map(fn (string $tagName) => '#'.str_replace(' ', '', $tagName))
            ->implode(' ');

        return '🔗 '.$this->title
            .PHP_EOL.$this->threadsUrl()
            .PHP_EOL.$tags;
    }
}
