<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\Threads\Threads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'services.threads.app_id' => 'test-app-id',
        'services.threads.app_secret' => 'test-app-secret',
        'services.threads.redirect_uri' => 'https://example.com/callback',
    ]);
});

it('redirects to Threads OAuth authorization page', function () {
    $threads = new Threads();

    $response = $threads->authenticate();

    expect($response->getTargetUrl())->toContain('https://threads.net/oauth/authorize')
        ->and($response->getTargetUrl())->toContain('client_id=test-app-id')
        ->and($response->getTargetUrl())->toContain('redirect_uri=https://example.com/callback')
        ->and($response->getTargetUrl())->toContain('scope=threads_basic,threads_content_publish')
        ->and($response->getTargetUrl())->toContain('response_type=code');
});

it('rejects non-string Threads configuration', function () {
    config(['services.threads.app_id' => ['invalid']]);

    expect(fn () => new Threads())->toThrow(InvalidArgumentException::class);
});

it('exchanges authorization code for tokens on callback', function () {
    Http::fake([
        'https://graph.threads.net/oauth/access_token*' => Http::response([
            'user_id' => 'test-user-id',
            'access_token' => 'short-lived-token',
        ], 200),
        'https://graph.threads.net/access_token*' => Http::response([
            'access_token' => 'long-lived-token',
            'expires_in' => 5183999,
        ], 200),
    ]);

    $user = User::factory()->create();

    $request = Request::create('/threads/callback', 'GET', [
        'code' => 'authorization-code',
    ]);
    $request->setUserResolver(fn () => $user);

    $threads = new Threads();
    $threads->authenticateCallback($request);

    $user->refresh();

    expect($user->threads_user_id)->toBe('test-user-id')
        ->and($user->threads_access_token)->toBe('long-lived-token')
        ->and($user->threads_access_token_expires_at)->not->toBeNull();
});

it('rejects an unauthenticated OAuth callback before making requests', function () {
    Http::preventStrayRequests();

    $request = Request::create('/threads/callback', 'GET', [
        'code' => 'authorization-code',
    ]);

    expect(fn () => (new Threads())->authenticateCallback($request))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'An authenticated user is required.');
});

it('does not save a malformed short-lived token response', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://graph.threads.net/oauth/access_token*' => Http::response([
            'user_id' => 123,
            'access_token' => 'malformed-short-token',
        ], 200),
        'https://graph.threads.net/access_token*' => Http::response([
            'access_token' => 'long-lived-token',
            'expires_in' => 5183999,
        ], 200),
    ]);

    $user = User::factory()->create([
        'threads_user_id' => 'original-user-id',
        'threads_access_token' => 'original-token',
    ]);

    $request = Request::create('/threads/callback', 'GET', ['code' => 'authorization-code']);
    $request->setUserResolver(fn () => $user);

    expect(fn () => (new Threads())->authenticateCallback($request))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'Invalid Threads token response.');

    expect($user->refresh()->threads_user_id)->toBe('original-user-id')
        ->and($user->threads_access_token)->toBe('original-token');
    Http::assertSentCount(1);
});

it('does not save a long-lived token with a non-numeric expiry', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://graph.threads.net/oauth/access_token*' => Http::response([
            'user_id' => 'test-user-id',
            'access_token' => 'short-lived-token',
        ], 200),
        'https://graph.threads.net/access_token*' => Http::response([
            'access_token' => 'malformed-long-token',
            'expires_in' => 'invalid',
        ], 200),
    ]);

    $user = User::factory()->create(['threads_access_token' => 'original-token']);
    $request = Request::create('/threads/callback', 'GET', ['code' => 'authorization-code']);
    $request->setUserResolver(fn () => $user);

    expect(fn () => (new Threads())->authenticateCallback($request))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'Invalid Threads token response.');

    expect($user->refresh()->threads_access_token)->toBe('short-lived-token')
        ->and($user->threads_access_token)->not->toBe('malformed-long-token');
});

it('does not save a malformed refreshed token response', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://graph.threads.net/refresh_access_token*' => Http::response([
            'access_token' => ['invalid'],
            'expires_in' => 5183999,
        ], 200),
    ]);

    $user = User::factory()->create([
        'threads_access_token' => 'original-token',
        'threads_access_token_expires_at' => now()->addDays(2),
    ]);

    expect(fn () => (new Threads())->refreshToken($user))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'Invalid Threads token response.');

    expect($user->refresh()->threads_access_token)->toBe('original-token');
});

it('aborts when OAuth callback has error parameter', function () {
    $user = User::factory()->create();

    $request = Request::create('/threads/callback', 'GET', [
        'error' => 'access_denied',
        'error_description' => 'User cancelled the dialog',
    ]);
    $request->setUserResolver(fn () => $user);

    $threads = new Threads();

    expect(fn () => $threads->authenticateCallback($request))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class);
});

it('uses a safe message when the OAuth error description is malformed', function () {
    $request = Request::create('/threads/callback', 'GET', [
        'error' => 'access_denied',
        'error_description' => ['invalid'],
    ]);

    expect(fn () => (new Threads())->authenticateCallback($request))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'Threads authentication failed.');
});

it('writes post to Threads via two-step API', function () {
    Http::fake([
        'graph.threads.net/v1.0/*/threads' => Http::response(['id' => 'media-container-id'], 200),
        'graph.threads.net/v1.0/*/threads_publish' => Http::response(['id' => 'published-id'], 200),
    ]);

    $user = User::factory()->create([
        'threads_user_id' => 'test-user-id',
        'threads_access_token' => 'test-token',
    ]);

    $threads = new Threads();
    $threads->writePost($user, '🔗 Test Post');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://graph.threads.net/v1.0/test-user-id/threads'
            && $request['media_type'] === 'TEXT'
            && $request['text'] === '🔗 Test Post'
            && $request['access_token'] === 'test-token';
    });

    Http::assertSent(function ($request) {
        return $request->url() === 'https://graph.threads.net/v1.0/test-user-id/threads_publish'
            && $request['creation_id'] === 'media-container-id'
            && $request['access_token'] === 'test-token';
    });
});

it('aborts when media container creation fails', function () {
    Http::fake([
        'graph.threads.net/v1.0/*/threads' => Http::response(['error' => 'Invalid token'], 400),
    ]);

    $user = User::factory()->create([
        'threads_user_id' => 'test-user-id',
        'threads_access_token' => 'test-token',
    ]);

    $threads = new Threads();

    expect(fn () => $threads->writePost($user, 'Test Content'))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'Failed to write post to Threads.');
});

it('aborts when publishing media fails', function () {
    Http::fake([
        'graph.threads.net/v1.0/*/threads' => Http::response(['id' => 'media-container-id'], 200),
        'graph.threads.net/v1.0/*/threads_publish' => Http::response(['error' => 'Publish failed'], 400),
    ]);

    $user = User::factory()->create([
        'threads_user_id' => 'test-user-id',
        'threads_access_token' => 'test-token',
    ]);

    $threads = new Threads();

    expect(fn () => $threads->writePost($user, 'Test Content'))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'Failed to publish post to Threads.');
});

it('refreshes access token and updates expiry', function () {
    Http::fake([
        'https://graph.threads.net/refresh_access_token*' => Http::response([
            'access_token' => 'refreshed-token',
            'expires_in' => 5183999,
        ], 200),
    ]);

    $user = User::factory()->create([
        'threads_access_token' => 'expiring-token',
        'threads_access_token_expires_at' => now()->addDays(5),
    ]);

    $threads = new Threads();
    $threads->refreshToken($user);

    $user->refresh();

    expect($user->threads_access_token)->toBe('refreshed-token')
        ->and(strtotime($user->threads_access_token_expires_at))->toBeGreaterThan(time());
});

it('aborts when token refresh fails', function () {
    Http::fake([
        'graph.threads.net/refresh_access_token' => Http::response(['error' => 'Invalid token'], 400),
    ]);

    $user = User::factory()->create([
        'threads_access_token' => 'invalid-token',
        'threads_access_token_expires_at' => now()->addDays(5),
    ]);

    $threads = new Threads();

    expect(fn () => $threads->refreshToken($user))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'Failed to refresh Threads access token.');
});

it('aborts when short-lived token request fails with error message', function () {
    Http::fake([
        'https://graph.threads.net/oauth/access_token*' => Http::response([
            'error' => [
                'message' => 'Invalid authorization code',
            ],
        ], 400),
    ]);

    $user = User::factory()->create();

    $request = Request::create('/threads/callback', 'GET', [
        'code' => 'invalid-code',
        'error_message' => 'The authorization code is invalid or has expired',
    ]);
    $request->setUserResolver(fn () => $user);

    $threads = new Threads();

    expect(fn () => $threads->authenticateCallback($request))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'The authorization code is invalid or has expired');
});

it('aborts when long-lived token request fails with error message', function () {
    Http::fake([
        'https://graph.threads.net/oauth/access_token*' => Http::response([
            'user_id' => 'test-user-id',
            'access_token' => 'short-lived-token',
        ], 200),
        'https://graph.threads.net/access_token*' => Http::response([
            'error' => [
                'message' => 'Invalid grant',
            ],
        ], 400),
    ]);

    $user = User::factory()->create();

    $request = Request::create('/threads/callback', 'GET', [
        'code' => 'valid-code',
        'error_message' => 'Failed to exchange token',
    ]);
    $request->setUserResolver(fn () => $user);

    $threads = new Threads();

    expect(fn () => $threads->authenticateCallback($request))
        ->toThrow(Symfony\Component\HttpKernel\Exception\HttpException::class, 'Failed to exchange token');
});
