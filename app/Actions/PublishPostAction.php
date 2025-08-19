<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\CreateOgImageJob;
use App\Jobs\PostToDevToJob;
use App\Jobs\PostToThreadsJob;
use App\Jobs\PostToTweetJob;
use App\Jobs\PurgeCacheContentJob;
use App\Models\Post;
use Illuminate\Support\Facades\Bus;

final readonly class PublishPostAction
{
    public function execute(Post $post): void
    {
        $post->is_published = true;

        if (! $post->published_at) {
            $post->published_at = now();
        }

        $post->saveQuietly();

        Bus::chain([
            new CreateOgImageJob($post),
            new PostToTweetJob($post)->delay(now()->addSeconds(20)),
            new PostToDevToJob($post),
            new PostToThreadsJob($post),
            new PurgeCacheContentJob([route('home')]),
        ])->dispatch();
    }
}
