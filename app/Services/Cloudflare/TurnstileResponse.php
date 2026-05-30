<?php

declare(strict_types=1);

namespace App\Services\Cloudflare;

use JsonSerializable;

final readonly class TurnstileResponse implements JsonSerializable
{
    public function __construct(
        private bool $success,
        private array $errorCodes,
    ) {}

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getErrorCodes(): array
    {
        return $this->errorCodes;
    }

    public function jsonSerialize(): array
    {
        return [
            'success' => $this->success,
            'error-codes' => $this->errorCodes,
        ];
    }
}
