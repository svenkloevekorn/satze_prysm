<?php

namespace App\Models;

use Database\Factories\InfluencerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'name',
    'country',
    'bio',
    'email',
    'contact_phone',
    'is_active',
    'notes',
])]
class Influencer extends Model implements HasMedia
{
    /** @use HasFactory<InfluencerFactory> */
    use HasFactory;

    use InteractsWithMedia;

    public function channels(): MorphMany
    {
        return $this->morphMany(SocialChannel::class, 'owner');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
