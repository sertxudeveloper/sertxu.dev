<?php

declare(strict_types=1);

use App\Services\Cloudflare\TurnstileResponse;

it('serializes a successful response', function () {
    $response = new TurnstileResponse(success: true, errorCodes: []);

    expect($response->jsonSerialize())->toBe([
        'success' => true,
        'error-codes' => [],
    ]);
});

it('serializes a failed response with error codes', function () {
    $response = new TurnstileResponse(success: false, errorCodes: ['invalid-input-response']);

    expect($response->jsonSerialize())->toBe([
        'success' => false,
        'error-codes' => ['invalid-input-response'],
    ]);
});

it('reports the success state', function () {
    $response = new TurnstileResponse(success: true, errorCodes: []);

    expect($response->isSuccess())->toBeTrue();
});

it('returns the error codes', function () {
    $response = new TurnstileResponse(success: false, errorCodes: ['timeout-or-duplicate', 'invalid-input-response']);

    expect($response->getErrorCodes())->toBe(['timeout-or-duplicate', 'invalid-input-response']);
});
