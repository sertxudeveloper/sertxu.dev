<?php

declare(strict_types=1);

use App\Actions\PublishPostAction;
use App\Jobs\CreateOgImageJob;
use App\Jobs\PostToDevToJob;
use App\Jobs\PostToThreadsJob;
use App\Jobs\PostToTweetJob;
use App\Jobs\PurgeCacheContentJob;
use App\Models\Post;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Facades\Bus;

it('publishes a post and dispatches the job chain', function () {
    Bus::fake();

    $post = Post::factory()->create(['is_published' => false]);

    $action = new PublishPostAction();
    $action->execute($post);

    expect($post->refresh()->is_published)->toBeTrue()
        ->and($post->refresh()->published_at)->not->toBeNull();

    Bus::assertChained([
        new CreateOgImageJob($post),
        PostToTweetJob::class,
        new PostToDevToJob($post),
        new PostToThreadsJob($post),
        new PurgeCacheContentJob([route('home'), route('posts.index')]),
        CallQueuedClosure::class,
    ]);
});

it('preserves existing published_at date when publishing', function () {
    Bus::fake();

    $post = Post::factory()->create([
        'is_published' => false,
        'published_at' => now()->subDay(),
    ]);

    $action = new PublishPostAction();
    $action->execute($post);

    expect($post->refresh()->published_at->isYesterday())->toBeTrue();
});

it('sets published_at to now when not already set', function () {
    Bus::fake();

    $this->travelTo(now()->setTime(12, 0, 0));

    $post = Post::factory()->create([
        'is_published' => false,
        'published_at' => null,
    ]);

    $action = new PublishPostAction();
    $action->execute($post);

    expect($post->refresh()->published_at->format('H:i:s'))->toBe('12:00:00');
});
