<?php

declare(strict_types=1);

use App\Jobs\PostToTweetJob;
use App\Models\Post;
use App\Services\Twitter\Twitter;
use Illuminate\Queue\Middleware\Skip;

it('publishes the post to twitter and marks as posted', function () {
    $post = Post::factory()->published()->create([
        'posted_on_twitter' => false,
        'title' => 'Test Post',
    ]);

    $job = new PostToTweetJob($post);
    $job->handle(app(Twitter::class));

    expect((bool) $post->refresh()->posted_on_twitter)->toBeTrue();
});

it('has skip middleware to prevent reposting', function () {
    $post = Post::factory()->published()->create(['posted_on_twitter' => true]);

    $job = new PostToTweetJob($post);
    $middleware = $job->middleware();

    expect($middleware)->toHaveCount(1)
        ->and($middleware[0])->toBeInstanceOf(Skip::class);
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
