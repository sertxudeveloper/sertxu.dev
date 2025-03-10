<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Post;
use App\Services\DevTo\DevTo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\NoReturn;

final class PostToDevToJob implements ShouldQueue
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
            Skip::when(fn () => $this->post->posted_on_dev),
        ];
    }

    /**
     * Handle the job.
     */
    #[NoReturn]
    public function handle(DevTo $devTo): void
    {
        $devTo->writePost(
            title: $this->post->title,
            markdown: $this->post->text,
            imageUrl: route('posts.thumbnail', $this->post),
            canonicalUrl: $this->post->url(),
            description: $this->post->excerpt,
            tags: $this->post->tags->pluck('name')->toArray(),
        );

        $this->post->updateQuietly(['posted_on_dev' => true]);
    }
}
