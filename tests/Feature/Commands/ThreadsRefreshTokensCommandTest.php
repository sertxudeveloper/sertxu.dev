<?php

declare(strict_types=1);

use App\Console\Commands\ThreadsRefreshTokensCommand;
use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'services.threads.app_id' => 'test-app-id',
        'services.threads.app_secret' => 'test-app-secret',
        'services.threads.redirect_uri' => 'https://example.com/callback',
    ]);
});

it('refreshes tokens for users with expiring tokens', function () {
    Http::fake([
        'graph.threads.net/*' => Http::response([
            'access_token' => 'new-token',
            'expires_in' => 5183999,
        ], 200),
    ]);

    $user = User::factory()->create([
        'threads_access_token' => 'old-token',
        'threads_access_token_expires_at' => now()->addDays(5),
    ]);

    $this->artisan(ThreadsRefreshTokensCommand::class)
        ->assertSuccessful()
        ->expectsOutput('Refreshing Threads access tokens for 1 users.')
        ->expectsOutput('Tokens refreshed successfully.');

    $user->refresh();
    expect($user->threads_access_token)->toBe('new-token');
});

it('does nothing when no users have expiring tokens', function () {
    User::factory()->create([
        'threads_access_token' => 'valid-token',
        'threads_access_token_expires_at' => now()->addDays(30),
    ]);

    $this->artisan(ThreadsRefreshTokensCommand::class)
        ->assertSuccessful();
});

it('does nothing when no users have threads tokens', function () {
    User::factory()->create([
        'threads_access_token' => null,
        'threads_access_token_expires_at' => null,
    ]);

    $this->artisan(ThreadsRefreshTokensCommand::class)
        ->assertSuccessful();
});

it('refreshes tokens for multiple users with expiring tokens', function () {
    Http::fake([
        'graph.threads.net/*' => Http::response([
            'access_token' => 'new-token',
            'expires_in' => 5183999,
        ], 200),
    ]);

    $user1 = User::factory()->create([
        'threads_access_token' => 'old-token-1',
        'threads_access_token_expires_at' => now()->addDays(5),
    ]);

    $user2 = User::factory()->create([
        'threads_access_token' => 'old-token-2',
        'threads_access_token_expires_at' => now()->addDays(8),
    ]);

    User::factory()->create([
        'threads_access_token' => 'valid-token',
        'threads_access_token_expires_at' => now()->addDays(30),
    ]);

    $this->artisan(ThreadsRefreshTokensCommand::class)
        ->assertSuccessful()
        ->expectsOutput('Refreshing Threads access tokens for 2 users.')
        ->expectsOutput('Tokens refreshed successfully.');

    expect($user1->refresh()->threads_access_token)->toBe('new-token')
        ->and($user2->refresh()->threads_access_token)->toBe('new-token');
});

it('includes users whose tokens expire exactly at the threshold', function () {
    Http::fake([
        'graph.threads.net/*' => Http::response([
            'access_token' => 'new-token',
            'expires_in' => 5183999,
        ], 200),
    ]);

    $user = User::factory()->create([
        'threads_access_token' => 'old-token',
        'threads_access_token_expires_at' => now()->addDays(10),
    ]);

    $this->artisan(ThreadsRefreshTokensCommand::class)
        ->assertSuccessful();

    expect($user->refresh()->threads_access_token)->toBe('new-token');
});
