<?php

declare(strict_types=1);

use App\Jobs\PostToDevToJob;
use App\Models\Post;
use App\Services\DevTo\DevTo;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Http;

it('publishes the post to dev.to and marks as posted', function () {
    Http::fake([
        'dev.to/*' => Http::response(['id' => 123, 'url' => 'https://dev.to/test'], 201),
    ]);

    $post = Post::factory()->published()->create([
        'posted_on_dev' => false,
        'title' => 'Test Post',
        'text' => 'Test content',
    ]);

    $job = new PostToDevToJob($post);
    $job->handle(app(DevTo::class));

    expect($post->refresh()->posted_on_dev)->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->hasHeader('api-key')
            && $request['article']['title'] === 'Test Post';
    });
});

it('has skip middleware to prevent reposting', function () {
    $post = Post::factory()->published()->create(['posted_on_dev' => true]);

    $job = new PostToDevToJob($post);
    $middleware = $job->middleware();

    expect($middleware)->toHaveCount(1)
        ->and($middleware[0])->toBeInstanceOf(Skip::class);
});

it('holds the post instance', function () {
    $post = Post::factory()->create();

    $job = new PostToDevToJob($post);

    $reflection = new ReflectionClass($job);
    $property = $reflection->getProperty('post');
    $property->setAccessible(true);

    expect($property->getValue($job)->is($post))->toBeTrue();
});

it('implements ShouldQueue', function () {
    $post = Post::factory()->create();

    $job = new PostToDevToJob($post);

    expect($job)->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueue::class);
});
