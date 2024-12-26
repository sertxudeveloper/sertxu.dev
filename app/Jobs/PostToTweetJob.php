<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Post;
use App\Services\Twitter\Twitter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\NoReturn;

final class PostToTweetJob implements ShouldQueue
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
            Skip::when(fn () => $this->post->posted_on_twitter),
        ];
    }

    /**
     * Handle the job.
     */
    #[NoReturn]
    public function handle(Twitter $twitter): void
    {
        $twitter->tweet($this->post->toTweet());

        $this->post->updateQuietly(['posted_on_twitter' => true]);
    }
}
