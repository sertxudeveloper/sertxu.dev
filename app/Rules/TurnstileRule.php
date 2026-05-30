<?php

declare(strict_types=1);

namespace App\Rules;

use App\Services\Cloudflare\TurnstileClient;
use App\Services\Cloudflare\TurnstileResponse;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final readonly class TurnstileRule implements ValidationRule
{
    private TurnstileClient $turnstileClient;

    public function __construct()
    {
        $this->turnstileClient = app(TurnstileClient::class);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = $this->turnstileClient->siteVerify($value);

        if (! $response->isSuccess()) {
            $this->handleErrorCodes($response, $fail);
        }
    }

    private function handleErrorCodes(TurnstileResponse $response, Closure $fail): void
    {
        foreach ($response->getErrorCodes() as $errorCode) {
            $fail($this->mapErrorCodeToMessage($errorCode));
        }
    }

    private function mapErrorCodeToMessage(string $code): string
    {
        return match ($code) {
            'missing-input-secret' => 'The secret parameter was not passed.',
            'invalid-input-secret' => 'The secret parameter was invalid or did not exist.',
            'missing-input-response' => 'The response parameter was not passed.',
            'invalid-input-response' => 'The response parameter is invalid or has expired.',
            'bad-request' => 'The request was rejected because it was malformed.',
            'timeout-or-duplicate' => 'The response parameter has already been validated before.',
            'internal-error' => 'An internal error happened while validating the response.',
            default => 'An unexpected error occurred.',
        };
    }
}
