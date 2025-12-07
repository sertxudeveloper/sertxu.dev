<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

it('returns 404 for thumbnail of an unpublished post', function () {
    $post = Post::factory()->create(['title' => 'Post A']);

    $this->get("/blog/$post->slug/thumbnail")
        ->assertNotFound();
});

it('can show post thumbnail of a published post', function (): void {
    $post = Post::factory()->published()->create(['title' => 'Post A']);
    $post
        ->addMediaFromBase64('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==')
        ->setFileName('sample.png')
        ->toMediaCollection('thumbnail');

    $this->get("/blog/$post->slug/thumbnail")
        ->assertRedirect();
});
