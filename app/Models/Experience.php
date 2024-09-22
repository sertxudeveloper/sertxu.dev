<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'title', 'description', 'started_at', 'ended_at', 'location',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime:Y-m-d',
            'ended_at' => 'datetime:Y-m-d',
        ];
    }

    /**
     * Scope a query to order by default.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDefaultOrder(Builder $query): Builder
    {
        return $query->orderByDesc('started_at');
    }
}