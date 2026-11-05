<?php

declare(strict_types=1);

use App\Filament\Resources\Users\UserResource;
use App\Models\Project;
use App\Models\User;

it('returns email as global search result title', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    expect(UserResource::getGlobalSearchResultTitle($user))->toBe('test@example.com');
});

it('returns an empty title for a non-user record', function () {
    expect(UserResource::getGlobalSearchResultTitle(Project::factory()->make()))->toBe('');
});

it('returns searchable attributes for global search', function () {
    expect(UserResource::getGloballySearchableAttributes())
        ->toBe(['email', 'name']);
});
