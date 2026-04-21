<?php

namespace App\Models;

use Database\Factories\ProductShopEntryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'competitor_product_id',
    'shop_id',
    'product_url',
    'observed_price',
    'observed_at',
    'notes',
])]
class ProductShopEntry extends Model
{
    /** @use HasFactory<ProductShopEntryFactory> */
    use HasFactory;

    public function competitorProduct(): BelongsTo
    {
        return $this->belongsTo(CompetitorProduct::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    protected function casts(): array
    {
        return [
            'observed_price' => 'decimal:2',
            'observed_at' => 'date',
        ];
    }
}
