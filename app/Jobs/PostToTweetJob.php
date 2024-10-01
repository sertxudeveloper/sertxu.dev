<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Post;
use App\Services\Twitter\Twitter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\NoReturn;

final class PostToTweetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Post $post) {}

    #[NoReturn]
    public function handle(Twitter $twitter): void
    {
        if ($this->post->posted_on_twitter) {
            return;
        }

        $twitter->tweet($this->post->toTweet());

        $this->post->updateQuietly(['posted_on_twitter' => true]);
    }
}
