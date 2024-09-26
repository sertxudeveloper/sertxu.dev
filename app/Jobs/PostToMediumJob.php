<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\Medium\Medium;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;

class PostToMediumJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Post $post)
    {
    }

    #[NoReturn]
    public function handle(Medium $medium): void
    {
        if ($this->post->posted_on_medium) {
            return;
        }

        $markdown = '![](' . $this->post->getFirstMediaUrl('thumbnail') . ')'
            . PHP_EOL . '# ' . $this->post->title
            . PHP_EOL . '## ' . Str::limit(Str::before($this->post->text, PHP_EOL).'...')
            . PHP_EOL . $this->post->text;

        $medium->writePost(
            title: $this->post->title,
            markdown: $markdown,
            canonicalUrl: $this->post->url(),
            tags: $this->post->tags->pluck('name')->toArray(),
        );

        $this->post->updateQuietly(['posted_on_medium' => true]);
    }
}
