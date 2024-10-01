<?php

declare(strict_types=1);

namespace App\Services\DevTo;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final readonly class DevTo
{
    public function writePost(string $title, string $markdown, string $imageUrl, string $canonicalUrl, string $description, array $tags): Response
    {
        return Http::asJson()
            ->withHeaders([
                'api-key' => config('services.devto.api_key'),
            ])
            ->throw()
            ->post('https://dev.to/api/articles', [
                'article' => [
                    'title' => $title,
                    'body_markdown' => $markdown,
                    'published' => true,
                    'main_image' => $imageUrl,
                    'canonical_url' => $canonicalUrl,
                    'description' => $description,
                    'tags' => $tags,
                ],
            ]);
    }
}
