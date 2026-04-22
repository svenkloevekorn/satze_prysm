<?php

namespace App\Models;

use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable([
    'name',
    'description',
    'website',
    'country',
    'instagram',
    'facebook',
    'linkedin',
    'tiktok',
    'youtube',
    'notes',
    'is_active',
])]
class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    use LogsActivity;

    public function competitorProducts(): HasMany
    {
        return $this->hasMany(CompetitorProduct::class);
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
