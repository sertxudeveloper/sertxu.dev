<?php

declare(strict_types=1);

namespace App\Services\Cloudflare;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final readonly class Cloudflare
{
    private string $base_url;
    private string $zone_id;
    private string $api_key;

    public function __construct()
    {
        $this->base_url = 'https://api.cloudflare.com';
        $this->zone_id = config('services.cloudflare.zone_id');
        $this->api_key = config('services.cloudflare.api_key');
    }

    public function purgeCache(array $urls): Response
    {
        return Http::asJson()
            ->throw()
            ->baseUrl($this->base_url)
            ->withToken($this->api_key)
            ->post("/client/v4/zones/$this->zone_id/purge_cache", [
                'files' => $urls,
            ]);
    }
}
