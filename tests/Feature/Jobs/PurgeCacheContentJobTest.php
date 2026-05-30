<?php

declare(strict_types=1);

use App\Jobs\PurgeCacheContentJob;
use JTSmith\Cloudflare\Services\Cloudflare\CachePurgeService;

it('purges the specified URLs from Cloudflare cache', function () {
    Http::fake([
        'api.cloudflare.com/*' => Http::response(['result' => ['success' => true, 'id' => 'fake-id']], 200),
    ]);

    $urls = ['https://example.com', 'https://example.com/blog'];

    $job = new PurgeCacheContentJob($urls);
    $job->handle(app(CachePurgeService::class));

    Http::assertSent(function ($request) use ($urls) {
        return $request->hasHeader('Authorization')
            && $request['files'] === $urls;
    });
});
