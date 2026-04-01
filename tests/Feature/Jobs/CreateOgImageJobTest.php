<?php

declare(strict_types=1);

use App\Jobs\CreateOgImageJob;
use App\Models\Post;
use Illuminate\Support\Facades\Queue;

it('holds the post instance', function () {
    $post = Post::factory()->create();

    $job = new CreateOgImageJob($post);

    expect($job->post->is($post))->toBeTrue();
});

it('has correct retry count', function () {
    $post = Post::factory()->create();

    $job = new CreateOgImageJob($post);

    expect($job->tries)->toBe(2);
});

it('implements ShouldQueue', function () {
    $post = Post::factory()->create();

    $job = new CreateOgImageJob($post);

    expect($job)->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueue::class);
});

it('dispatches job to queue', function () {
    Queue::fake();

    $post = Post::factory()->create();

    CreateOgImageJob::dispatch($post);

    Queue::assertPushed(CreateOgImageJob::class, function ($job) use ($post) {
        return $job->post->is($post);
    });
});

it('renders thumbnail view for og image generation', function () {
    $post = Post::factory()->create(['title' => 'Test Post', 'slug' => 'test-post']);

    $html = view('posts.thumbnail', ['post' => $post])->render();

    expect($html)->toContain('Test Post');
});

it('generates og image and attaches to post media', function () {
    $post = Post::factory()->create();

    $job = new CreateOgImageJob($post);
    $job->handle();

    expect($post->getFirstMedia('thumbnail'))->not->toBeNull();
});
