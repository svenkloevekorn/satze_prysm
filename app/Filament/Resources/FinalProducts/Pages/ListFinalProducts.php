<?php

namespace App\Filament\Resources\FinalProducts\Pages;

use App\Filament\Resources\FinalProducts\FinalProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFinalProducts extends ListRecords
{
    protected static string $resource = FinalProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
