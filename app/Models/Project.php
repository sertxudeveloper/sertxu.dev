<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

final class Project extends Model implements HasMedia
{
    use HasFactory, HasTags, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'website', 'text', 'is_published', 'is_featured',
    ];

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnail')
            ->useDisk('public')
//            ->withResponsiveImages()
            ->singleFile();
    }

    /**
     * Register the media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Crop, 720, 405)
            ->format('webp')
            ->performOnCollections('thumbnail');

        $this->addMediaConversion('poster')
            ->fit(Fit::Crop, 1920, 1080)
            ->format('webp')
            ->performOnCollections('thumbnail');
    }

    /**
     * Scope a query to order projects by default.
     */
    public function scopeDefaultOrder(Builder $query): Builder
    {
        return $query
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at');
    }

    /**
     * Scope a query to only include active projects.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('published_at', '<=', now());
    }

    public function scopePublished(Builder $query): void
    {
        $query
            ->where('is_published', true)
            ->orderBy('id', 'desc');
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
