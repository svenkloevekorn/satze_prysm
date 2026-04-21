<?php

namespace App\Filament\Resources\Ratings\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\Ratings\RatingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRating extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = RatingResource::class;
}
