<?php

namespace App\Filament\Resources\RatingDimensions\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\RatingDimensions\RatingDimensionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRatingDimension extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = RatingDimensionResource::class;
}
