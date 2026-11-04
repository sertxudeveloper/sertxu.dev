<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Override;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Tags\HasTags;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property ?string $excerpt
 * @property ?string $text
 * @property ?string $website
 * @property ?bool $is_published
 * @property bool $is_featured
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 * @property ?string $repository
 */
#[UseFactory(ProjectFactory::class)]
final class Project extends Model implements HasMedia, Sitemapable
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory, HasTags, InteractsWithMedia, SoftDeletes;

    /**
     * The relationships that should always be loaded.
     *
     * @var list<string>
     */
    #[Override]
    public $with = ['tags'];

    /**
     * The attributes that are mass-assignable.
     *
     * @var list<string>
     */
    #[Override]
    protected $fillable = [
        'title', 'slug', 'excerpt', 'website', 'repository', 'text', 'is_published', 'is_featured',
    ];

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnail')
            ->singleFile();
    }

    /**
     * Register the media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $thumbnail = $this->addMediaConversion('thumbnail');
        $thumbnail->performOnCollections('thumbnail');
        $thumbnail
            ->fit(Fit::Crop, 720, 405)
            ->format('webp');

        $poster = $this->addMediaConversion('poster');
        $poster->performOnCollections('thumbnail');
        $poster
            ->fit(Fit::Crop, 1920, 1080)
            ->format('webp');
    }

    /**
     * Scope a query to order projects by default.
     *
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeDefaultOrder(Builder $query): Builder
    {
        return $query
            ->latest('is_featured')
            ->latest('created_at')
            ->latest('id');
    }

    /**
     * Scope a query to get only the published projects.
     *
     * @param  Builder<Project>  $query
     */
    public function scopeWherePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * Get the project as a sitemap tag.
     */
    public function toSitemapTag(): Url
    {
        $url = Url::create(route('projects.show', $this));

        if ($this->updated_at !== null) {
            $url->setLastModificationDate($this->updated_at);
        }

        return $url
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1);
    }

    /**
     * Determine if the project is published.
     */
    public function isPublished(): bool
    {
        return $this->is_published ?? false;
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
            'is_featured' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
