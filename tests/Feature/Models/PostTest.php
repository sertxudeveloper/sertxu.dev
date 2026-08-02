<?php

declare(strict_types=1);

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

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

it('returns show route when post is published', function () {
    $post = Post::factory()->published()->create();

    expect($post->url())->toContain($post->slug);
});

it('returns preview route when post is not published', function () {
    $post = Post::factory()->create();

    expect($post->url())->toContain('preview');
});

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

it('includes utm parameters for twitter', function () {
    $post = Post::factory()->published()->create();

    $url = $post->tweetUrl();

    expect($url)->toContain('utm_source=twitter')
        ->and($url)->toContain('utm_medium=post');
});

it('includes utm parameters for threads', function () {
    $post = Post::factory()->published()->create();

    $url = $post->threadsUrl();

    expect($url)->toContain('utm_source=threads')
        ->and($url)->toContain('utm_medium=post');
});

it('includes utm parameters for linkedin', function () {
    $post = Post::factory()->published()->create();

    $url = $post->linkedinUrl();

    expect($url)->toContain('utm_source=linkedin')
        ->and($url)->toContain('utm_medium=post');
});

it('generates a sitemap URL with yearly frequency', function () {
    $post = Post::factory()->published()->create();

    $sitemapTag = $post->toSitemapTag();

    expect($sitemapTag->url)->toContain($post->slug)
        ->and($sitemapTag->priority)->toBe(0.1)
        ->and($sitemapTag->changeFrequency)->toBe('yearly');
});

it('returns the latest posts excluding itself when there are no tags', function () {
    $post = Post::factory()->published()->create(['published_at' => now()->subDays(4)]);
    $secondNewest = Post::factory()->published()->create(['published_at' => now()->subDays(2)]);
    $newest = Post::factory()->published()->create(['published_at' => now()]);
    Post::factory()->published()->create(['published_at' => now()->subDays(3)]);

    $related = $post->relatedPosts();

    expect($related->pluck('id')->toArray())->toBe([$newest->id, $secondNewest->id]);
});

it('returns only posts sharing at least one tag', function () {
    $tag = Spatie\Tags\Tag::create(['name' => 'Laravel']);

    $post = Post::factory()->published()->create(['published_at' => now()->subDay()]);
    $post->attachTag($tag);

    $sharing = Post::factory()->published()->create(['published_at' => now()]);
    $sharing->attachTag($tag);

    $other = Post::factory()->published()->create(['published_at' => now()]);

    $related = $post->relatedPosts();

    expect($related->pluck('id'))->toContain($sharing->id)
        ->and($related->pluck('id'))->not->toContain($other->id);
});
it('calculates one minute per 240 words', function () {
    $post = Post::factory()->create(['text' => implode(' ', array_fill(0, 240, 'word'))]);

    expect($post->minutes_to_read)->toBe(1.0);
});

it('rounds partial minutes up', function () {
    $post = Post::factory()->create(['text' => implode(' ', array_fill(0, 241, 'word'))]);

    expect($post->minutes_to_read)->toBe(2.0);
});

it('returns zero for an empty post', function () {
    $post = Post::factory()->create(['text' => '']);

    expect($post->minutes_to_read)->toBe(0.0);
});

it('registers a single-file thumbnail collection', function () {
    $post = Post::factory()->create();

    $post->registerMediaCollections();

    $collection = $post->getRegisteredMediaCollections()->first();

    expect($collection->name)->toBe('thumbnail')
        ->and($collection->singleFile)->toBeTrue();
});

it('registers thumbnail conversions', function () {
    $post = Post::factory()->create();

    $post->registerMediaConversions(null);

    $conversions = $post->mediaConversions;

    expect($conversions)->toHaveCount(2)
        ->and($conversions[0]->getName())->toBe('thumbnail')
        ->and($conversions[1]->getName())->toBe('thumbnail-jpg');
});
