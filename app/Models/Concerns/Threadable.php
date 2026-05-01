<?php

declare(strict_types=1);

namespace App\Models\Concerns;

trait Threadable
{
    abstract public function threadsUrl(): string;

    public function toThreads(): array
    {
        $primaryTag = null;
        $primaryTagForContent = '';

        if ($this->tags->isNotEmpty()) {
            $firstName = $this->tags->first()->name;
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
