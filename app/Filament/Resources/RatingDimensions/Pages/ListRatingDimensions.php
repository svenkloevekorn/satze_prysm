<?php

namespace App\Filament\Resources\RatingDimensions\Pages;

use App\Filament\Resources\RatingDimensions\RatingDimensionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRatingDimensions extends ListRecords
{
    protected static string $resource = RatingDimensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
