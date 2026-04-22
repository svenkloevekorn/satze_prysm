<?php

namespace App\Models;

use Database\Factories\QualityCriterionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['name', 'description', 'is_active'])]
class QualityCriterion extends Model
{
    /** @use HasFactory<QualityCriterionFactory> */
    use HasFactory;

    use LogsActivity;

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
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
