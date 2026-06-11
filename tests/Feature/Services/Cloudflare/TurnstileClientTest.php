<?php

declare(strict_types=1);

use App\Services\Cloudflare\TurnstileClient;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['services.turnstile.secret' => 'test-secret-key']);
});

it('returns success when Cloudflare verifies the token', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'error-codes' => [],
        ], 200),
    ]);

    $client = new TurnstileClient();
    $response = $client->siteVerify('valid-token');

    expect($response->isSuccess())->toBeTrue()
        ->and($response->getErrorCodes())->toBe([]);
});

it('returns failure when Cloudflare rejects the token', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => false,
            'error-codes' => ['invalid-input-response'],
        ], 200),
    ]);

    $client = new TurnstileClient();
    $response = $client->siteVerify('invalid-token');

    expect($response->isSuccess())->toBeFalse()
        ->and($response->getErrorCodes())->toContain('invalid-input-response');
});

it('sends the secret key and response token', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'error-codes' => [],
        ], 200),
    ]);

    $client = new TurnstileClient();
    $client->siteVerify('my-token');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://challenges.cloudflare.com/turnstile/v0/siteverify'
            && $request['secret'] === 'test-secret-key'
            && $request['response'] === 'my-token';
    });
});

it('throws when the HTTP response is a server error', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(null, 500),
    ]);

    $client = new TurnstileClient();

    expect(fn () => $client->siteVerify('token'))
        ->toThrow(Illuminate\Http\Client\RequestException::class);
});

it('handles multiple error codes', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => false,
            'error-codes' => ['timeout-or-duplicate', 'invalid-input-response'],
        ], 200),
    ]);

    $client = new TurnstileClient();
    $response = $client->siteVerify('stale-token');

    expect($response->isSuccess())->toBeFalse()
        ->and($response->getErrorCodes())->toHaveCount(2);
});
