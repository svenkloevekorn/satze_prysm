<?php

namespace App\Filament\Resources\Brands\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\Brands\BrandResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBrand extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
