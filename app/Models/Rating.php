<?php

namespace App\Models;

use App\Enums\RatingType;
use Database\Factories\RatingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'ratable_type',
    'ratable_id',
    'rating_dimension_id',
    'user_id',
    'type',
    'score',
    'comment',
    'positives',
    'negatives',
    'rated_at',
])]
class Rating extends Model
{
    /** @use HasFactory<RatingFactory> */
    use HasFactory;

    public function ratable(): MorphTo
    {
        return $this->morphTo();
    }

    public function dimension(): BelongsTo
    {
        return $this->belongsTo(RatingDimension::class, 'rating_dimension_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'type' => RatingType::class,
            'rated_at' => 'date',
        ];
    }
}
