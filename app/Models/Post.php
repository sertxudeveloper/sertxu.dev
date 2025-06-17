<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\Threadable;
use App\Models\Concerns\Tweetable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Tags\HasTags;

final class Post extends Model implements HasMedia, Sitemapable
{
    use HasFactory, HasTags, InteractsWithMedia, SoftDeletes, Threadable, Tweetable;

    /**
     * The relationships that should always be loaded.
     *
     * @var list<string>
     */
    public $with = ['tags'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title', 'slug', 'text', 'is_published', 'published_at',
        'posted_on_twitter', 'posted_on_dev', 'posted_on_threads',
    ];

    /**
     * Get the next free publish date.
     */
    public static function nextFreePublishDate(): Carbon
    {
        // Set the publishing date to 8:00 AM UTC tomorrow (Spain: 9 AM winter time / 10 AM summer time)
        $publishDate = now()->hour(8)->addDay();

        // If the date falls on a weekend or a post already exists on this date, increment the date
        while ($publishDate->isWeekend() || self::whereDate('published_at', $publishDate)->exists()) {
            $publishDate->addDay();
        }

        return $publishDate;
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnail')
            ->useDisk('r2')
            ->singleFile();
    }

    /**
     * Register the media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Crop, 640, 360)
            ->optimize()
            ->format('webp')
            ->performOnCollections('thumbnail');

        $this->addMediaConversion('thumbnail-jpg')
            ->fit(Fit::Crop, 640, 360)
            ->optimize()
            ->format('jpg')
            ->performOnCollections('thumbnail');
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished(Builder $query): void
    {
        $query
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc');
    }

    /**
     * Scope a query to only include scheduled posts.
     */
    public function scopeScheduled(Builder $query): void
    {
        $query
            ->where('is_published', false)
            ->whereNotNull('published_at');
    }

    /**
     * Get the tweet URL.
     */
    public function tweetUrl(): string
    {
        return route('posts.show', [$this, 'utm_source' => 'twitter', 'utm_medium' => 'post']);
    }

    /**
     * Get the URL.
     */
    public function url(): string
    {
        if ($this->is_published) {
            return route('posts.show', $this);
        }

        return route('posts.preview', $this);
    }

    /**
     * Determine if the post is published.
     */
    public function isPublished(): bool
    {
        return $this->is_published && $this->published_at <= now();
    }

    /**
     * Get the threads URL.
     */
    public function threadsUrl(): string
    {
        return route('posts.show', [$this, 'utm_source' => 'threads', 'utm_medium' => 'post']);
    }

    /**
     * Get the excerpt attribute.
     *
     * @return Attribute<string>
     */
    public function excerpt(): Attribute
    {
        return new Attribute(
            get: fn () => Str::limit(Str::before($this->text, PHP_EOL)),
        );
    }

    /**
     * Get the post as a sitemap tag.
     */
    public function toSitemapTag(): Url
    {
        return Url::create(route('posts.show', $this))
            ->setLastModificationDate(Carbon::create($this->updated_at))
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1);
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'posted_on_twitter' => 'boolean',
            'posted_on_dev' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
