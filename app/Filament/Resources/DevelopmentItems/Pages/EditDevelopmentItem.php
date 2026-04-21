<?php

namespace App\Filament\Resources\DevelopmentItems\Pages;

use App\Filament\Resources\DevelopmentItems\DevelopmentItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDevelopmentItem extends EditRecord
{
    protected static string $resource = DevelopmentItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
