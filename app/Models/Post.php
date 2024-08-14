<?php

namespace App\Models;

use App\Jobs\GeneratePostThumbnail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'thumbnail', 'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to order projects by default.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDefaultOrder(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }

    /**
     * Get the thumbnail URL attribute.
     *
     * @return Attribute
     */
    public function thumbnailUrl(): Attribute
    {
        return new Attribute(
            get: fn() => Storage::disk('public')->url($this->thumbnail)
        );
    }

    /**
     * Scope a query to only include active projects.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('published_at', '<=', now());
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            // If the post is not a draft, set the published_at attribute to the current date and time.
            if (! $post->is_draft) {
                $post->published_at = now();
            }
        });

        static::created(function (Post $post) {
            // If the post is a draft, don't generate a thumbnail.
            // Also, if the post already has a thumbnail, don't generate a new one.
            if (! $post->is_draft && ! $post->thumbnail) {
                GeneratePostThumbnail::dispatch($post);
            }
        });

        static::updating(function (Post $post) {
            // If the post is not a draft, set the published_at attribute to the current date and time.
            if (! $post->is_draft) {
                if (! $post->published_at) $post->published_at = now();
            } else {
                // If the post is a draft, set the published_at attribute to null.
                $post->published_at = null;
            }
        });

        static::updated(function (Post $post) {
            // When the post is not a draft.
            if (! $post->is_draft) {
                if (! $post->thumbnail) {
                    // If the post doesn't have a thumbnail, generate one.
                    GeneratePostThumbnail::dispatch($post);
                } else if ($post->isDirty('title')) {
                    // If the title has changed, generate a new thumbnail.
                    GeneratePostThumbnail::dispatch($post);
                }
            }
        });
    }
}
