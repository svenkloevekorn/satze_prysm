<?php

namespace App\Filament\Resources\FinalProducts\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\FinalProducts\FinalProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFinalProduct extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = FinalProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
