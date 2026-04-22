<?php

namespace App\Models;

use App\Enums\RatingSource;
use Database\Factories\RatingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable([
    'ratable_type',
    'ratable_id',
    'rating_dimension_id',
    'user_id',
    'sources',
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

    use LogsActivity;

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
            'sources' => AsEnumCollection::of(RatingSource::class),
            'rated_at' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
