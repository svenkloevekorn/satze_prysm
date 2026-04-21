<?php

namespace App\Filament\Resources\CompetitorProducts\Pages;

use App\Filament\Imports\CompetitorProductImporter;
use App\Filament\Resources\CompetitorProducts\CompetitorProductResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListCompetitorProducts extends ListRecords
{
    protected static string $resource = CompetitorProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('CSV-Import')
                ->importer(CompetitorProductImporter::class),
            CreateAction::make()->label('Neues Wettbewerbsprodukt'),
        ];
    }
}
