<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Panel;

it('allows admin users to access the panel', function () {
    $user = User::factory()->admin()->create();

    expect($user->canAccessPanel(Panel::make()))->toBeTrue();
});

it('denies non-admin users from accessing the panel', function () {
    $user = User::factory()->create();

    expect($user->canAccessPanel(Panel::make()))->toBeFalse();
});
