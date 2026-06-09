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
        ->assertSeeText('3 posts so far')
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

it('shows total post count on index page', function (): void {
    Post::factory()->published()->count(5)->create();

    $this->get('/blog')
        ->assertOk()
        ->assertSeeText('5 posts so far');
});

it('shows filtered results count when searching', function (): void {
    Post::factory()->published()->create(['title' => 'Laravel Tips']);
    Post::factory()->published()->count(3)->create();

    $this->get('/blog?search=Laravel')
        ->assertOk()
        ->assertSeeText('1 post found for');
});

it('returns 404 for an unpublished post', function () {
    $post = Post::factory()->create();

    $this->get("/blog/$post->slug")
        ->assertNotFound();
});
