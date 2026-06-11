<?php

declare(strict_types=1);

use App\Rules\TurnstileRule;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['services.turnstile.secret' => 'test-secret-key']);
});

it('passes when Cloudflare verifies the token', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'error-codes' => [],
        ], 200),
    ]);

    $rule = new TurnstileRule();
    $fail = fn () => throw new RuntimeException('Should not be called');

    $rule->validate('cf-turnstile-response', 'valid-token', $fail);

    expect(true)->toBeTrue();
});

it('fails when Cloudflare rejects the token', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => false,
            'error-codes' => ['invalid-input-response'],
        ], 200),
    ]);

    $rule = new TurnstileRule();
    $failMessage = null;
    $fail = function (string $message) use (&$failMessage) {
        $failMessage = $message;
    };

    $rule->validate('cf-turnstile-response', 'invalid-token', $fail);

    expect($failMessage)->toBe('The response parameter is invalid or has expired.');
});

it('fails with missing-input-response error code', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => false,
            'error-codes' => ['missing-input-response'],
        ], 200),
    ]);

    $rule = new TurnstileRule();
    $failMessage = null;
    $fail = function (string $message) use (&$failMessage) {
        $failMessage = $message;
    };

    $rule->validate('cf-turnstile-response', '', $fail);

    expect($failMessage)->toBe('The response parameter was not passed.');
});

it('fails with timeout-or-duplicate error code', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => false,
            'error-codes' => ['timeout-or-duplicate'],
        ], 200),
    ]);

    $rule = new TurnstileRule();
    $failMessage = null;
    $fail = function (string $message) use (&$failMessage) {
        $failMessage = $message;
    };

    $rule->validate('cf-turnstile-response', 'stale-token', $fail);

    expect($failMessage)->toBe('The response parameter has already been validated before.');
});

it('fails with unknown error code and returns generic message', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => false,
            'error-codes' => ['unknown-error'],
        ], 200),
    ]);

    $rule = new TurnstileRule();
    $failMessage = null;
    $fail = function (string $message) use (&$failMessage) {
        $failMessage = $message;
    };

    $rule->validate('cf-turnstile-response', 'token', $fail);

    expect($failMessage)->toBe('An unexpected error occurred.');
});
