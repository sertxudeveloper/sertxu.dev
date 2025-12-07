<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

it('cannot show post preview with non-authenticated user', function (): void {
    $post = Post::factory()->create(['title' => 'Post A']);

    $this->get("/blog/$post->slug/preview")
        ->assertNotFound();
});

it('can show post preview with authenticated user', function (): void {
    $this->actingAs($user = User::factory()->create());

    $post = Post::factory()->create(['title' => 'Post A']);

    $this->get("/blog/$post->slug/preview")
        ->assertOk()
        ->assertSeeText('Post A');
});
