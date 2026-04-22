<?php

namespace App\Models;

use App\Models\Concerns\HasQualityChecks;
use App\Models\Concerns\HasRatings;
use Database\Factories\FinalProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'development_item_id',
    'category_id',
    'name',
    'sku',
    'description',
    'cost_price',
    'retail_price',
    'launched_at',
])]
class FinalProduct extends Model implements HasMedia
{
    /** @use HasFactory<FinalProductFactory> */
    use HasFactory;

    use HasQualityChecks;
    use HasRatings;
    use InteractsWithMedia;

    public function developmentItem(): BelongsTo
    {
        return $this->belongsTo(DevelopmentItem::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'retail_price' => 'decimal:2',
            'launched_at' => 'date',
        ];
    }
}
