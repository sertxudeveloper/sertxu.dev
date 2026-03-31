<?php

declare(strict_types=1);

use App\Jobs\CreateOgImageJob;
use App\Models\Post;

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
