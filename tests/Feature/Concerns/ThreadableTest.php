<?php

declare(strict_types=1);

use App\Models\Post;
use Spatie\Tags\Tag;

it('formats a thread with title, url and tags', function () {
    $post = Post::factory()->create([
        'title' => 'Building a Laravel Package',
    ]);

    $tag = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
    $post->tags()->attach($tag->id);

    $thread = $post->toThreads();

    expect($thread)->toContain('🔗 Building a Laravel Package')
        ->toContain(route('posts.show', [$post, 'utm_source' => 'threads', 'utm_medium' => 'post']))
        ->toContain('#Laravel');
});

it('formats a thread with multiple tags', function () {
    $post = Post::factory()->create([
        'title' => 'Testing with Pest',
    ]);

    $tag1 = Tag::create(['name' => 'PHP', 'slug' => 'php']);
    $tag2 = Tag::create(['name' => 'Testing', 'slug' => 'testing']);
    $post->tags()->attach([$tag1->id, $tag2->id]);

    $thread = $post->toThreads();

    expect($thread)->toContain('#PHP')
        ->toContain('#Testing');
});

it('formats a thread with tags containing spaces', function () {
    $post = Post::factory()->create([
        'title' => 'Vue.js Tips',
    ]);

    $tag = Tag::create(['name' => 'Vue JS', 'slug' => 'vue-js']);
    $post->tags()->attach($tag->id);

    $thread = $post->toThreads();

    expect($thread)->toContain('#VueJS');
});

it('formats a thread without tags', function () {
    $post = Post::factory()->create([
        'title' => 'Hello World',
    ]);

    $thread = $post->toThreads();

    expect($thread)->toContain('🔗 Hello World')
        ->not->toContain('#');
});
