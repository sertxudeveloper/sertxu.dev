<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Spatie\Tags\Tag;

trait Threadable
{
    abstract public function threadsUrl(): string;

    /**
     * @return array{content: string, topic_tag: string|null}
     */
    public function toThreads(): array
    {
        $primaryTag = null;
        $primaryTagForContent = '';

        $firstTag = $this->tags->first();

        if ($firstTag instanceof Tag) {
            $name = $firstTag->getAttribute('name');
            $firstName = is_string($name) ? $name : '';
            $primaryTag = str_replace([' ', '.', '&', '@', '!', '?', ',', ';', ':'], '', $firstName);
            if ($primaryTag === '') {
                $primaryTag = null;
            } else {
                $primaryTagForContent = '#'.$primaryTag;
            }
        }

        $content = '🔗 '.$this->title
            .PHP_EOL."\n"
            .PHP_EOL.$this->threadsUrl()
            .($primaryTagForContent ? PHP_EOL.$primaryTagForContent : '');

        return [
            'content' => $content,
            'topic_tag' => $primaryTag,
        ];
    }
}
