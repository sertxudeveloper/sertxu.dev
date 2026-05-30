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

        return new TurnstileResponse(
            success: $response->json('success'),
            errorCodes: $response->json('error-codes')
        );
    }
}
