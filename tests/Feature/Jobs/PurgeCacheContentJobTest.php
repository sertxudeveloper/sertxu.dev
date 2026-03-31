<?php

declare(strict_types=1);

use App\Jobs\PurgeCacheContentJob;
use App\Services\Cloudflare\Cloudflare;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'services.cloudflare.zone_id' => 'test-zone-id',
        'services.cloudflare.api_key' => 'test-api-key',
    ]);
});

it('purges the specified URLs from Cloudflare cache', function () {
    Http::fake([
        'api.cloudflare.com/*' => Http::response(['success' => true], 200),
    ]);

    $urls = ['https://example.com', 'https://example.com/blog'];

    $job = new PurgeCacheContentJob($urls);
    $job->handle(app(Cloudflare::class));

    Http::assertSent(function ($request) use ($urls) {
        return $request->hasHeader('Authorization')
            && $request['files'] === $urls;
    });
});

it('holds the URLs instance', function () {
    $urls = ['https://example.com/page1', 'https://example.com/page2'];

    $job = new PurgeCacheContentJob($urls);

    $reflection = new ReflectionClass($job);
    $property = $reflection->getProperty('urls');
    $property->setAccessible(true);

    expect($property->getValue($job))->toBe($urls);
});

it('implements ShouldQueue', function () {
    $urls = ['https://example.com'];

    $job = new PurgeCacheContentJob($urls);

    expect($job)->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueue::class);
});
