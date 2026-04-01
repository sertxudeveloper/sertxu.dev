<?php

declare(strict_types=1);

use App\Filament\Resources\Users\UserResource;
use App\Models\User;

it('returns email as global search result title', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    expect(UserResource::getGlobalSearchResultTitle($user))->toBe('test@example.com');
});

it('returns searchable attributes for global search', function () {
    expect(UserResource::getGloballySearchableAttributes())
        ->toBe(['email', 'name']);
});
