<?php

declare(strict_types=1);

use App\Models\Post;
use Spatie\Tags\Tag;

it('formats a tweet with title, url and tags', function () {
    $post = Post::factory()->create([
        'title' => 'Building a Laravel Package',
    ]);

    $tag = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
    $post->tags()->attach($tag->id);

    $tweet = $post->toTweet();

    expect($tweet)->toContain('🔗 Building a Laravel Package')
        ->toContain(route('posts.show', [$post, 'utm_source' => 'twitter', 'utm_medium' => 'post']))
        ->toContain('#Laravel');
});

it('formats a tweet with multiple tags', function () {
    $post = Post::factory()->create([
        'title' => 'Testing with Pest',
    ]);

    $tag1 = Tag::create(['name' => 'PHP', 'slug' => 'php']);
    $tag2 = Tag::create(['name' => 'Testing', 'slug' => 'testing']);
    $post->tags()->attach([$tag1->id, $tag2->id]);

    $tweet = $post->toTweet();

    expect($tweet)->toContain('#PHP')
        ->toContain('#Testing');
});

it('formats a tweet with tags containing spaces', function () {
    $post = Post::factory()->create([
        'title' => 'Vue.js Tips',
    ]);

    $tag = Tag::create(['name' => 'Vue JS', 'slug' => 'vue-js']);
    $post->tags()->attach($tag->id);

    $tweet = $post->toTweet();

    expect($tweet)->toContain('#VueJS');
});

it('formats a tweet without tags', function () {
    $post = Post::factory()->create([
        'title' => 'Hello World',
    ]);

    $tweet = $post->toTweet();

    expect($tweet)->toContain('🔗 Hello World')
        ->not->toContain('#');
});
