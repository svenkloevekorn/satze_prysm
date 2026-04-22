<?php

namespace App\Models;

use App\Models\Concerns\HasQualityChecks;
use App\Models\Concerns\HasRatings;
use Database\Factories\CompetitorProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

#[Fillable([
    'name',
    'brand_id',
    'category_id',
    'description',
    'materials',
    'colors',
    'sizes',
    'price_min',
    'price_max',    'co2_kg',
    'recycled_content_pct',
    'certifications',
])]
class CompetitorProduct extends Model implements HasMedia
{
    /** @use HasFactory<CompetitorProductFactory> */
    use HasFactory;

    use HasQualityChecks;
    use HasRatings;
    use HasTags;
    use InteractsWithMedia;
    use LogsActivity;

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function shopEntries(): HasMany
    {
        return $this->hasMany(ProductShopEntry::class);
    }

    protected function casts(): array
    {
        return [
            'certifications' => 'array',
            'co2_kg' => 'decimal:2',
            'materials' => 'array',
            'colors' => 'array',
            'sizes' => 'array',
            'price_min' => 'decimal:2',
            'price_max' => 'decimal:2',
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
