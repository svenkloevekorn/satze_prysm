<?php

namespace App\Filament\Resources\CompetitorProducts\Pages;

use App\Filament\Exports\CompetitorProductExporter;
use App\Filament\Imports\CompetitorProductImporter;
use App\Filament\Resources\CompetitorProducts\CompetitorProductResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
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
            ExportAction::make()
                ->label('CSV-Export')
                ->exporter(CompetitorProductExporter::class),
            CreateAction::make()->label('Neues Wettbewerbsprodukt'),
        ];
    }
}
