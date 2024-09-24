<?php

namespace App\Actions;

use App\Jobs\CreateOgImageJob;
use App\Jobs\PostToDevToJob;
use App\Jobs\PostToMediumJob;
use App\Jobs\PostToTweetJob;
use App\Models\Post;
use Illuminate\Support\Facades\Bus;

class PublishPostAction
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
            (new PostToTweetJob($post))->delay(now()->addSeconds(20)),
            new PostToMediumJob($post),
            new PostToDevToJob($post),
        ])->dispatch();
    }
}
