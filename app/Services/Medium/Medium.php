<?php

declare(strict_types=1);

namespace App\Services\Medium;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final readonly class Medium
{
    private string $userId;

    public function __construct()
    {
        $this->userId = config('services.medium.user_id');
    }

    public function writePost(string $title, string $markdown, string $canonicalUrl, array $tags): Response
    {
        return Http::asJson()
            ->withToken(config('services.medium.api_key'))
            ->throw()
            ->post("https://api.medium.com/v1/users/$this->userId/posts", [
                'title' => $title,
                'contentFormat' => 'markdown',
                'content' => $markdown,
                'tags' => $tags,
                'canonicalUrl' => $canonicalUrl,
                'publishStatus' => 'public',
                'license' => 'all-rights-reserved',
                'notifyFollowers' => true,
            ]);

    }
}
