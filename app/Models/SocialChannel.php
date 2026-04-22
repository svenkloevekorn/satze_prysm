<?php

namespace App\Models;

use App\Enums\SocialPlatform;
use Database\Factories\SocialChannelFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable([
    'owner_type',
    'owner_id',
    'platform',
    'handle',
    'url',
    'followers',
    'engagement_rate',
    'language',
    'country',
    'categories',
    'is_active',
    'notes',
])]
class SocialChannel extends Model
{
    /** @use HasFactory<SocialChannelFactory> */
    use HasFactory;

    use LogsActivity;

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ChannelMetric::class);
    }

    public function latestMetric(): ?ChannelMetric
    {
        /** @var ChannelMetric|null $metric */
        $metric = $this->metrics()->latest('captured_at')->first();

        return $metric;
    }

    protected function casts(): array
    {
        return [
            'platform' => SocialPlatform::class,
            'followers' => 'integer',
            'engagement_rate' => 'decimal:2',
            'categories' => 'array',
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
