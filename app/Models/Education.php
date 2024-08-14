<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Education extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'started_at', 'ended_at', 'description', 'location',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date:Y-m-d',
            'ended_at' => 'date:Y-m-d',
        ];
    }

    /**
     * Scope a query to order by default.
     *
     * @param Builder $query
     * @return mixed
     */
    public function scopeDefaultOrder(Builder $query): Builder
    {
        return $query->orderByDesc('started_at');
    }
}
