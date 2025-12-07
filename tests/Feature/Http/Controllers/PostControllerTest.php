<?php

declare(strict_types=1);

use App\Models\Post;

it('can load blog index page', function (): void {
    Post::factory()->published()->create(['title' => 'Post A']);
    Post::factory()->published()->create(['title' => 'Post B']);
    Post::factory()->published()->create(['title' => 'Post C']);

    $this->get('/blog')
        ->assertOk()
        ->assertSeeText('Blog')
        ->assertSeeTextInOrder([
            'Post C',
            'Post B',
            'Post A',
        ]);
});

it('shows a published post', function (): void {
    $post = Post::factory()->published()->create(['title' => 'Post A']);

    $this->get("/blog/$post->slug")
        ->assertOk()
        ->assertSeeText('Post A');
});

it('returns 404 for an unpublished post', function () {
    $post = Post::factory()->create();

    $this->get("/blog/$post->slug")
        ->assertNotFound();
});
