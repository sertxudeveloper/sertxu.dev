<?php

declare(strict_types=1);

use App\Jobs\PostToDevToJob;
use App\Jobs\PostToThreadsJob;
use App\Jobs\PostToTweetJob;
use App\Jobs\PurgeCacheContentJob;
use App\Models\Post;
use App\Services\Cloudflare\Cloudflare;
use App\Services\DevTo\DevTo;
use Illuminate\Support\Facades\Http;

describe('PostToTweetJob', function () {
    it('skips execution when post already posted on twitter', function () {
        $post = Post::factory()->published()->create(['posted_on_twitter' => true]);

        $job = new PostToTweetJob($post);
        $middleware = $job->middleware();

        expect($middleware)->toHaveCount(1);
    });
});

describe('PostToDevToJob', function () {
    it('publishes the post to dev.to and marks as posted', function () {
        Http::fake([
            'dev.to/*' => Http::response(['id' => 123, 'url' => 'https://dev.to/test'], 201),
        ]);

        $post = Post::factory()->published()->create([
            'posted_on_dev' => false,
            'title' => 'Test Post',
            'text' => 'Test content',
        ]);

        $job = new PostToDevToJob($post);
        $job->handle(app(DevTo::class));

        expect($post->refresh()->posted_on_dev)->toBeTrue();

        Http::assertSent(function ($request) {
            return $request->hasHeader('api-key')
                && $request['article']['title'] === 'Test Post';
        });
    });

    it('skips execution when post already posted on dev.to', function () {
        $post = Post::factory()->published()->create(['posted_on_dev' => true]);

        $job = new PostToDevToJob($post);
        $middleware = $job->middleware();

        expect($middleware)->toHaveCount(1);
    });
});

describe('PostToThreadsJob', function () {
    it('skips execution when post already posted on threads', function () {
        $post = Post::factory()->published()->create(['posted_on_threads' => true]);

        $job = new PostToThreadsJob($post);
        $middleware = $job->middleware();

        expect($middleware)->toHaveCount(1);
    });
});

describe('PurgeCacheContentJob', function () {
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
});
