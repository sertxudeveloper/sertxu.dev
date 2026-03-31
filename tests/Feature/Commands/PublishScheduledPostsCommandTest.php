<?php

declare(strict_types=1);

use App\Console\Commands\PublishScheduledPostsCommand;
use App\Models\Post;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    Bus::fake();
});

it('publishes scheduled posts that are due', function () {
    $post = Post::factory()->create([
        'is_published' => false,
        'published_at' => now()->subHour(),
    ]);

    $this->artisan(PublishScheduledPostsCommand::class)
        ->assertSuccessful();

    expect($post->refresh()->is_published)->toBeTrue();
});

it('does not publish posts scheduled for the future', function () {
    $post = Post::factory()->create([
        'is_published' => false,
        'published_at' => now()->addHour(),
    ]);

    $this->artisan(PublishScheduledPostsCommand::class)
        ->assertSuccessful();

    expect($post->refresh()->is_published)->toBeFalse();
});

it('does not publish posts without a published_at date', function () {
    $post = Post::factory()->create([
        'is_published' => false,
        'published_at' => null,
    ]);

    $this->artisan(PublishScheduledPostsCommand::class)
        ->assertSuccessful();

    expect($post->refresh()->is_published)->toBeFalse();
});

it('does not publish already published posts', function () {
    $post = Post::factory()->create([
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    $this->artisan(PublishScheduledPostsCommand::class)
        ->assertSuccessful();

    expect($post->refresh()->is_published)->toBeTrue();
});

it('publishes multiple scheduled posts', function () {
    $post1 = Post::factory()->create([
        'is_published' => false,
        'published_at' => now()->subMinutes(30),
    ]);

    $post2 = Post::factory()->create([
        'is_published' => false,
        'published_at' => now()->subMinutes(10),
    ]);

    Post::factory()->create([
        'is_published' => false,
        'published_at' => now()->addHour(),
    ]);

    $this->artisan(PublishScheduledPostsCommand::class)
        ->assertSuccessful();

    expect($post1->refresh()->is_published)->toBeTrue()
        ->and($post2->refresh()->is_published)->toBeTrue();
});
