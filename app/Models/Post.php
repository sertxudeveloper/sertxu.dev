<?php

namespace App\Models;

use App\Actions\PublishPostAction;
use App\Models\Concerns\Threadable;
use App\Models\Concerns\Tweetable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasTags, InteractsWithMedia, Tweetable, Threadable;

    public $with = ['tags'];

    protected $fillable = [
        'title', 'slug', 'text', 'is_published', 'published_at', 'posted_on_twitter', 'posted_on_medium',
        'posted_on_dev',
    ];

    public static function booted(): void
    {
        static::saved(function (Post $post) {
            if ($post->is_published) {
                static::withoutEvents(function () use ($post) {
                    (new PublishPostAction())->execute($post);
                });
            }
        });
    }

    public static function nextFreePublishDate(): Carbon
    {
        $publishDate = now()->hour(14);

        // If the date falls on a weekend or a post already exists on this date, increment the date
        while ($publishDate->isWeekend() || Post::whereDate('published_at', $publishDate)->exists()) {
            $publishDate->addDay();
        }

        return $publishDate;
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnail')
            ->useDisk('public')
            ->singleFile();
    }

    public function scopePublished(Builder $query): void
    {
        $query
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc');
    }

    public function scopeScheduled(Builder $query): void
    {
        $query
            ->where('is_published', false)
            ->whereNotNull('published_at');
    }

    public function tweetUrl(): string
    {
        return route('posts.show', [$this, 'utm_source' => 'twitter', 'utm_medium' => 'post']);
    }

    public function url(): string
    {
        if ($this->is_published) {
            return route('posts.show', $this);
        }

        return route('posts.preview', $this);
    }

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'posted_on_twitter' => 'boolean',
            'posted_on_medium' => 'boolean',
            'posted_on_dev' => 'boolean',
        ];
    }

    public function threadsUrl(): string
    {
        return route('posts.show', [$this, 'utm_source' => 'threads', 'utm_medium' => 'post']);
    }
}
