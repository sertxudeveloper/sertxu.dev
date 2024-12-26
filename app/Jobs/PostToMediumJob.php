<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Post;
use App\Services\Medium\Medium;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\NoReturn;

final class PostToMediumJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Post $post) {}

    /**
     * Get the middleware the job should pass through.
     */
    public function middleware(): array
    {
        return [
            Skip::when(fn () => $this->post->posted_on_medium),
        ];
    }

    /**
     * Handle the job.
     */
    #[NoReturn]
    public function handle(Medium $medium): void
    {
        $markdown = '![]('.route('posts.thumbnail', $this->post).')'
            .PHP_EOL.'# '.$this->post->title
            .PHP_EOL.'## '.$this->post->excerpt
            .PHP_EOL.$this->post->text;

        $medium->writePost(
            title: $this->post->title,
            markdown: $markdown,
            canonicalUrl: $this->post->url(),
            tags: $this->post->tags->pluck('name')->toArray(),
        );

        $this->post->updateQuietly(['posted_on_medium' => true]);
    }
}
