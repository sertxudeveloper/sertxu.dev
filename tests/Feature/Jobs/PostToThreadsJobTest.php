<?php

declare(strict_types=1);

use App\Jobs\PostToThreadsJob;
use App\Models\Post;
use App\Models\User;
use App\Services\Threads\Threads;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'services.threads.app_id' => 'test-app-id',
        'services.threads.app_secret' => 'test-app-secret',
        'services.threads.redirect_uri' => 'https://example.com/callback',
    ]);
});

it('publishes the post to threads and marks as posted', function () {
    Http::fake([
        'graph.threads.net/*' => Http::response(['id' => '123'], 200),
    ]);

    User::factory()->create([
        'threads_user_id' => '9093830107340733',
        'threads_access_token' => 'test-token',
    ]);

    $post = Post::factory()->published()->create([
        'posted_on_threads' => false,
        'title' => 'Test Post',
    ]);

    $job = new PostToThreadsJob($post);
    $job->handle(app(Threads::class));

    Http::assertSentCount(2);
    expect((bool) $post->refresh()->posted_on_threads)->toBeTrue();
});

it('skips execution when post already posted on threads', function () {
    $post = Post::factory()->published()->create(['posted_on_threads' => true]);

    $job = new PostToThreadsJob($post);
    $middleware = $job->middleware();

    expect($middleware)->toHaveCount(1);
});

it('does not skip when post has not been posted on threads', function () {
    $post = Post::factory()->published()->create(['posted_on_threads' => false]);

    $job = new PostToThreadsJob($post);
    $middleware = $job->middleware();

    expect($middleware)->toHaveCount(1);
});

it('holds the post instance', function () {
    $post = Post::factory()->create();

    $job = new PostToThreadsJob($post);

    $reflection = new ReflectionClass($job);
    $property = $reflection->getProperty('post');
    $property->setAccessible(true);

    expect($property->getValue($job)->is($post))->toBeTrue();
});

it('implements ShouldQueue', function () {
    $post = Post::factory()->create();

    $job = new PostToThreadsJob($post);

    expect($job)->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueue::class);
});
