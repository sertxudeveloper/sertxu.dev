<?php

declare(strict_types=1);

use App\Models\Post;
use Spatie\Tags\Tag;

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

it('skips tag filter if query empty', function (): void {
    Post::factory()->published()->count(3)->create();

    $this->get('/blog?tag=')
        ->assertOk()
        ->assertSeeText('3 posts so far');
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

it('returns 404 for a published post scheduled in the future', function (): void {
    $post = Post::factory()->create([
        'is_published' => true,
        'published_at' => now()->addDay(),
    ]);

    $this->get("/blog/$post->slug")
        ->assertNotFound();
});

it('filters posts by tag', function (): void {
    $laravel = Tag::create(['name' => 'Laravel']);
    $php = Tag::create(['name' => 'PHP']);

    $laravelPost = Post::factory()->published()->create(['title' => 'Laravel Post']);
    $laravelPost->attachTag($laravel);

    $phpPost = Post::factory()->published()->create(['title' => 'PHP Post']);
    $phpPost->attachTag($php);

    $this->get('/blog?tag=laravel')
        ->assertOk()
        ->assertSeeText('Laravel Post')
        ->assertDontSeeText('PHP Post');
});

it('filters posts by search in the title', function (): void {
    Post::factory()->published()->create(['title' => 'Laravel Tips']);
    Post::factory()->published()->create(['title' => 'Docker Guide']);

    $this->get('/blog?search=Laravel')
        ->assertOk()
        ->assertSeeText('Laravel Tips')
        ->assertDontSeeText('Docker Guide');
});

it('filters posts by search in the excerpt', function (): void {
    Post::factory()->published()->create([
        'title' => 'First Post',
        'excerpt' => 'A guide about kubernetes',
    ]);
    Post::factory()->published()->create([
        'title' => 'Second Post',
        'excerpt' => 'Unrelated content',
    ]);

    $this->get('/blog?search=kubernetes')
        ->assertOk()
        ->assertSeeText('First Post')
        ->assertDontSeeText('Second Post');
});

it('paginates posts showing 12 on the first page', function (): void {
    Post::factory()->published()->count(12)->create(['published_at' => now()->subHours(2)]);
    Post::factory()->published()->create([
        'title' => 'Oldest Post On Page Two',
        'published_at' => now()->subDay(),
    ]);

    $this->get('/blog')
        ->assertOk()
        ->assertSeeText('13 posts so far')
        ->assertDontSeeText('Oldest Post On Page Two');

    $this->get('/blog?page=2')
        ->assertOk()
        ->assertSeeText('Oldest Post On Page Two');
});
