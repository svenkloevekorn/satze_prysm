<?php

namespace App\Filament\Resources\RatingDimensions\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\RatingDimensions\RatingDimensionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRatingDimension extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = RatingDimensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
