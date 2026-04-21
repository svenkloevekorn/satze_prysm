<?php

namespace App\Filament\Resources\DevelopmentItems\Pages;

use App\Filament\Resources\DevelopmentItems\DevelopmentItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDevelopmentItems extends ListRecords
{
    protected static string $resource = DevelopmentItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
