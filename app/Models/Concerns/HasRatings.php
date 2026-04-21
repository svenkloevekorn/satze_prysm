<?php

namespace App\Models\Concerns;

use App\Models\Rating;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasRatings
{
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'ratable');
    }

    public function averageScore(): ?float
    {
        $avg = $this->ratings()->avg('score');

        return $avg !== null ? (float) round($avg, 1) : null;
    }
}
