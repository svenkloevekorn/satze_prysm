<?php

namespace App\Models;

use App\Enums\DevelopmentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'name',
    'category_id',
    'user_id',
    'status',
    'description',
    'materials',
    'colors',
    'sizes',
    'target_price',
    'notes',
    'deadline',
])]
class DevelopmentItem extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\DevelopmentItemFactory> */
    use HasFactory;
    use InteractsWithMedia;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function competitorInspirations(): BelongsToMany
    {
        return $this->belongsToMany(CompetitorProduct::class);
    }

    public function supplierBasis(): BelongsToMany
    {
        return $this->belongsToMany(SupplierProduct::class);
    }

    public function finalProduct(): HasOne
    {
        return $this->hasOne(FinalProduct::class);
    }

    public function isFinal(): bool
    {
        return $this->status === DevelopmentStatus::Final;
    }

    protected static function booted(): void
    {
        static::saved(function (DevelopmentItem $item) {
            if ($item->status !== DevelopmentStatus::Final) {
                return;
            }

            FinalProduct::firstOrCreate(
                ['development_item_id' => $item->id],
                [
                    'name' => $item->name,
                    'category_id' => $item->category_id,
                    'description' => $item->description,
                    'retail_price' => $item->target_price,
                    'launched_at' => now()->toDateString(),
                ],
            );
        });
    }

    protected function casts(): array
    {
        return [
            'status' => DevelopmentStatus::class,
            'materials' => 'array',
            'colors' => 'array',
            'sizes' => 'array',
            'target_price' => 'decimal:2',
            'deadline' => 'date',
        ];
    }
}
