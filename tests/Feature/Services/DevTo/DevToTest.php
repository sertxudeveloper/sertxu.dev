<?php

declare(strict_types=1);

use App\Services\DevTo\DevTo;
use Illuminate\Support\Facades\Http;

it('posts an article to dev.to with the expected payload', function () {
    Http::fake([
        'dev.to/*' => Http::response(['id' => 123], 201),
    ]);

    config(['services.devto.api_key' => 'test-api-key']);

    $devTo = new DevTo();
    $devTo->writePost(
        title: 'My Post Title',
        markdown: '# Hello',
        imageUrl: 'https://example.com/thumbnail.webp',
        canonicalUrl: 'https://sertxu.dev/blog/my-post',
        description: 'A short description',
        tags: ['laravel', 'php'],
    );

    Http::assertSent(function ($request) {
        return $request->method() === 'POST'
            && $request->url() === 'https://dev.to/api/articles'
            && $request->hasHeader('api-key', 'test-api-key')
            && $request['article'] === [
                'title' => 'My Post Title',
                'body_markdown' => '# Hello',
                'published' => true,
                'main_image' => 'https://example.com/thumbnail.webp',
                'canonical_url' => 'https://sertxu.dev/blog/my-post',
                'description' => 'A short description',
                'tags' => ['laravel', 'php'],
            ];
    });
});

it('throws a request exception when dev.to responds with a server error', function () {
    Http::fake([
        'dev.to/*' => Http::response(null, 500),
    ]);

    $devTo = new DevTo();
    $devTo->writePost('Title', 'Body', 'image', 'url', 'description', ['laravel']);
})->throws(Illuminate\Http\Client\RequestException::class);
