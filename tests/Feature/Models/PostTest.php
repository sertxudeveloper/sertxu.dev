<?php

declare(strict_types=1);

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

describe('Post model', function () {
    describe('nextFreePublishDate', function () {
        it('returns tomorrow at 8am UTC for a weekday with no existing posts', function () {
            $this->travelTo(Carbon::create(2025, 8, 18, 10, 0));

            $nextDate = Post::nextFreePublishDate();

            expect($nextDate->format('Y-m-d H:i:s'))->toBe('2025-08-19 08:00:00');
        });

        it('skips weekends when finding next publish date', function () {
            $this->travelTo(Carbon::create(2025, 8, 22, 10, 0));

            $nextDate = Post::nextFreePublishDate();

            expect($nextDate->isWeekend())->toBeFalse();
        });

        it('skips dates that already have posts', function () {
            $this->travelTo(Carbon::create(2025, 8, 18, 10, 0));

            Post::factory()->create(['published_at' => Carbon::create(2025, 8, 19, 8, 0)]);

            $nextDate = Post::nextFreePublishDate();

            expect($nextDate->format('Y-m-d H:i:s'))->toBe('2025-08-20 08:00:00');
        });
    });

    describe('isPublished', function () {
        it('returns true when post is published and published_at is in the past', function () {
            $post = Post::factory()->published()->create([
                'published_at' => now()->subHour(),
            ]);

            expect($post->isPublished())->toBeTrue();
        });

        it('returns false when post is published but published_at is in the future', function () {
            $post = Post::factory()->create([
                'is_published' => true,
                'published_at' => now()->addHour(),
            ]);

            expect($post->isPublished())->toBeFalse();
        });

        it('returns false when post is not published', function () {
            $post = Post::factory()->create([
                'is_published' => false,
            ]);

            expect($post->isPublished())->toBeFalse();
        });
    });

    describe('url', function () {
        it('returns show route when post is published', function () {
            $post = Post::factory()->published()->create();

            expect($post->url())->toContain($post->slug);
        });

        it('returns preview route when post is not published', function () {
            $post = Post::factory()->create();

            expect($post->url())->toContain('preview');
        });
    });

    describe('excerpt', function () {
        it('returns limited first line of text', function () {
            $post = Post::factory()->create([
                'text' => 'This is the first line'.PHP_EOL.'This is the second line',
            ]);

            expect($post->excerpt)->toBe('This is the first line');
        });

        it('returns limited text when there is no newline', function () {
            $longText = str_repeat('a', 300);
            $post = Post::factory()->create([
                'text' => $longText,
            ]);

            expect(mb_strlen($post->excerpt))->toBe(103);
        });
    });

    describe('scopes', function () {
        it('only returns published posts with wherePublished scope', function () {
            $published = Post::factory()->published()->create();
            $unpublished = Post::factory()->create(['is_published' => false]);

            $posts = Post::wherePublished()->get();

            expect($posts->pluck('id')->contains($published->id))->toBeTrue()
                ->and($posts->pluck('id')->contains($unpublished->id))->toBeFalse();
        });

        it('returns scheduled posts with whereScheduled scope', function () {
            $scheduled = Post::factory()->create([
                'is_published' => false,
                'published_at' => now()->addDay(),
            ]);
            $published = Post::factory()->published()->create();

            $posts = Post::whereScheduled()->get();

            expect($posts->pluck('id')->contains($scheduled->id))->toBeTrue()
                ->and($posts->pluck('id')->contains($published->id))->toBeFalse();
        });

        it('orders published posts by published_at descending', function () {
            $postA = Post::factory()->published()->create(['published_at' => now()->subDays(2)]);
            $postB = Post::factory()->published()->create(['published_at' => now()->subDay()]);
            $postC = Post::factory()->published()->create(['published_at' => now()]);

            $posts = Post::wherePublished()->get();

            expect($posts->pluck('id')->toArray())->toBe([$postC->id, $postB->id, $postA->id]);
        });
    });

    describe('tweetUrl', function () {
        it('includes utm parameters for twitter', function () {
            $post = Post::factory()->published()->create();

            $url = $post->tweetUrl();

            expect($url)->toContain('utm_source=twitter')
                ->and($url)->toContain('utm_medium=post');
        });
    });

    describe('threadsUrl', function () {
        it('includes utm parameters for threads', function () {
            $post = Post::factory()->published()->create();

            $url = $post->threadsUrl();

            expect($url)->toContain('utm_source=threads')
                ->and($url)->toContain('utm_medium=post');
        });
    });

    describe('linkedinUrl', function () {
        it('includes utm parameters for linkedin', function () {
            $post = Post::factory()->published()->create();

            $url = $post->linkedinUrl();

            expect($url)->toContain('utm_source=linkedin')
                ->and($url)->toContain('utm_medium=post');
        });
    });
});
