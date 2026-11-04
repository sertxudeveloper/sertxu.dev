<?php

declare(strict_types=1);

namespace App\Services\Cloudflare;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class TurnstileClient
{
    private const string TURNSTILE_VERIFY_ENDPOINT = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    private const int RETRY_ATTEMPTS = 3;

    private const int RETRY_DELAY = 100;

    public function siteVerify(string $response): TurnstileResponse
    {
        $verificationResponse = $this->sendTurnstileVerificationRequest($response);

        return $this->parseVerificationResponse($verificationResponse);
    }

    private function sendTurnstileVerificationRequest(string $response): Response
    {
        return Http::retry(self::RETRY_ATTEMPTS, self::RETRY_DELAY)
            ->asForm()
            ->acceptJson()
            ->post(self::TURNSTILE_VERIFY_ENDPOINT, [
                'secret' => config()->string('services.turnstile.secret'),
                'response' => $response,
            ]);
    }

    private function parseVerificationResponse(Response $response): TurnstileResponse
    {
        if (! $response->ok()) {
            return new TurnstileResponse(success: false, errorCodes: []);
        }

        $payload = $response->json();
        $success = is_array($payload) ? ($payload['success'] ?? null) : null;
        $rawErrorCodes = is_array($payload) ? ($payload['error-codes'] ?? null) : null;
        $errorCodes = [];
        $hasValidErrorCodes = is_array($rawErrorCodes) && array_is_list($rawErrorCodes);

        if ($hasValidErrorCodes) {
            foreach ($rawErrorCodes as $errorCode) {
                if (! is_string($errorCode)) {
                    $hasValidErrorCodes = false;

                    continue;
                }

                $errorCodes[] = $errorCode;
            }
        }

        return new TurnstileResponse(
            success: $success === true && $hasValidErrorCodes && $errorCodes === [],
            errorCodes: $errorCodes,
        );
    }
}
