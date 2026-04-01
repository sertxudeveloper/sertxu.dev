<?php

declare(strict_types=1);

use App\Livewire\Posts;
use App\Models\Post;

it('renders successfully', function () {
    livewire(Posts::class)
        ->assertSuccessful();
});

it('displays published posts', function () {
    $posts = Post::factory()->published()->count(3)->create();

    livewire(Posts::class)
        ->assertSee($posts->first()->title);
});

it('does not display unpublished posts', function () {
    Post::factory()->create(['is_published' => false]);

    livewire(Posts::class)
        ->assertDontSee('Unpublished');
});

it('filters posts by tag', function () {
    $post = Post::factory()->published()->create();
    $post->attachTags(['laravel']);

    livewire(Posts::class, ['tag' => 'laravel'])
        ->assertSee($post->title);
});

it('clears selected tag', function () {
    $component = livewire(Posts::class, ['tag' => 'laravel']);

    $component->call('clearSelectedTag');

    $component->assertSet('tag', '');
});

it('searches posts by title', function () {
    $post = Post::factory()->published()->create(['title' => 'Unique Search Title']);
    Post::factory()->published()->create(['title' => 'Other Post']);

    livewire(Posts::class, ['query' => 'Unique Search Title'])
        ->assertSee('Unique Search Title')
        ->assertDontSee('Other Post');
});

it('searches posts by content', function () {
    $post = Post::factory()->published()->create(['text' => 'This post contains a unique phrase']);
    Post::factory()->published()->create(['text' => 'Different content here']);

    livewire(Posts::class, ['query' => 'unique phrase'])
        ->assertSee($post->title);
});

it('paginates posts', function () {
    Post::factory()->published()->count(20)->create();

    livewire(Posts::class)
        ->assertSuccessful();
});
