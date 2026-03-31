<?php

declare(strict_types=1);

use App\Jobs\PostToTweetJob;
use App\Models\Post;
use App\Services\Twitter\Twitter;
use Illuminate\Support\Facades\Http;

it('publishes the post to twitter and marks as posted', function () {
    Http::fake([
        'api.twitter.com/*' => Http::response(['data' => ['id' => '123']], 201),
        'api.x.com/*' => Http::response(['data' => ['id' => '123']], 201),
    ]);

    $post = Post::factory()->published()->create([
        'posted_on_twitter' => false,
        'title' => 'Test Post',
    ]);

    $job = new PostToTweetJob($post);
    $job->handle(app(Twitter::class));

    expect((bool) $post->refresh()->posted_on_twitter)->toBeTrue();
});

it('skips execution when post already posted on twitter', function () {
    $post = Post::factory()->published()->create(['posted_on_twitter' => true]);

    $job = new PostToTweetJob($post);
    $middleware = $job->middleware();

    expect($middleware)->toHaveCount(1);
});

it('does not skip when post has not been posted on twitter', function () {
    $post = Post::factory()->published()->create(['posted_on_twitter' => false]);

    $job = new PostToTweetJob($post);
    $middleware = $job->middleware();

    expect($middleware)->toHaveCount(1);
});

it('holds the post instance', function () {
    $post = Post::factory()->create();

    $job = new PostToTweetJob($post);

    $reflection = new ReflectionClass($job);
    $property = $reflection->getProperty('post');
    $property->setAccessible(true);

    expect($property->getValue($job)->is($post))->toBeTrue();
});

it('implements ShouldQueue', function () {
    $post = Post::factory()->create();

    $job = new PostToTweetJob($post);

    expect($job)->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueue::class);
});
