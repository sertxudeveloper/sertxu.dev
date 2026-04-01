<?php

declare(strict_types=1);

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;

it('returns title as global search result title', function () {
    $post = Post::factory()->create(['title' => 'My Blog Post']);

    expect(PostResource::getGlobalSearchResultTitle($post))->toBe('My Blog Post');
});

it('returns searchable attributes for global search', function () {
    expect(PostResource::getGloballySearchableAttributes())
        ->toBe(['title', 'text']);
});
