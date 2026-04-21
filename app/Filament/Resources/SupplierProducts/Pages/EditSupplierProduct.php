<?php

namespace App\Filament\Resources\SupplierProducts\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\SupplierProducts\SupplierProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSupplierProduct extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = SupplierProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
