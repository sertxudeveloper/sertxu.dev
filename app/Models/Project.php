<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'website', 'content', 'thumbnail', 'published_at', 'is_featured',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function ($project) {
            Storage::disk('public')->delete($project->thumbnail);
        });
    }

    /**
     * Scope a query to order projects by default.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDefaultOrder(Builder $query): Builder
    {
        return $query
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at');
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
     * Get the attributes that should be cast.
     *
     * @return array
     */

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }
}
