<?php

namespace App\Models;

use App\Enums\DevelopmentStatus;
use App\Models\Concerns\HasQualityChecks;
use Database\Factories\DevelopmentItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

/**
 * @property DevelopmentStatus $status
 * @property int $id
 * @property ?string $name
 * @property ?int $category_id
 * @property ?string $description
 * @property ?float $target_price
 */
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
    /** @use HasFactory<DevelopmentItemFactory> */
    use HasFactory;

    use HasQualityChecks;
    use HasTags;
    use InteractsWithMedia;
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
