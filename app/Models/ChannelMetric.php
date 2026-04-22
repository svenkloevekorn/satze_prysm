<?php

namespace App\Models;

use Database\Factories\ChannelMetricFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'social_channel_id',
    'captured_at',
    'followers',
    'posts_count',
    'avg_likes',
    'avg_comments',
    'engagement_rate',
])]
class ChannelMetric extends Model
{
    /** @use HasFactory<ChannelMetricFactory> */
    use HasFactory;

    public function channel(): BelongsTo
    {
        return $this->belongsTo(SocialChannel::class, 'social_channel_id');
    }

    protected function casts(): array
    {
        return [
            'captured_at' => 'date',
            'followers' => 'integer',
            'posts_count' => 'integer',
            'avg_likes' => 'integer',
            'avg_comments' => 'integer',
            'engagement_rate' => 'decimal:2',
        ];
    }
}
