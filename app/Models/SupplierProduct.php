<?php

namespace App\Models;

use App\Models\Concerns\HasQualityChecks;
use App\Models\Concerns\HasRatings;
use Database\Factories\SupplierProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

#[Fillable([
    'supplier_id',
    'category_id',
    'name',
    'description',
    'purchase_price',
    'recommended_retail_price',
    'moq',
    'materials',
    'colors',
    'sizes',
    'notes',    'co2_kg',
    'recycled_content_pct',
    'certifications',
])]
class SupplierProduct extends Model implements HasMedia
{
    /** @use HasFactory<SupplierProductFactory> */
    use HasFactory;

    use HasQualityChecks;
    use HasRatings;
    use HasTags;
    use InteractsWithMedia;
    use LogsActivity;

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function casts(): array
    {
        return [
            'certifications' => 'array',
            'co2_kg' => 'decimal:2',
            'materials' => 'array',
            'colors' => 'array',
            'sizes' => 'array',
            'purchase_price' => 'decimal:2',
            'recommended_retail_price' => 'decimal:2',
            'moq' => 'integer',
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
