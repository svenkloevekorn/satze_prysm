<?php

namespace App\Models;

use App\Enums\QualityCheckStatus;
use Database\Factories\QualityCheckFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable([
    'checkable_type',
    'checkable_id',
    'quality_criterion_id',
    'user_id',
    'status',
    'comment',
    'checked_at',
])]
class QualityCheck extends Model
{
    /** @use HasFactory<QualityCheckFactory> */
    use HasFactory;

    use LogsActivity;

    public function checkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(QualityCriterion::class, 'quality_criterion_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'status' => QualityCheckStatus::class,
            'checked_at' => 'date',
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
