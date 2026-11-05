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
        $content = '';

        $firstTag = $this->tags->first();

        if ($firstTag instanceof Tag) {
            $name = $firstTag->getAttribute('name');
            $name = is_string($name) ? $name : '';
            $name = str_replace([' '], '', $name);

            if ($name) {
                $primaryTag = $name;

                $content = '🔗 '.$this->title
                    .PHP_EOL."\n"
                    .PHP_EOL.$this->threadsUrl()
                    .PHP_EOL."#$primaryTag";
            }
        }

        if (! $content) {
            $content = '🔗 '.$this->title
                .PHP_EOL."\n"
                .PHP_EOL.$this->threadsUrl();
        }

        return [
            'content' => $content,
            'topic_tag' => $primaryTag,
        ];
    }
}
