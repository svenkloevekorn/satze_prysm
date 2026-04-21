<?php

namespace App\Filament\Resources\SupplierProducts\Pages;

use App\Filament\Exports\SupplierProductExporter;
use App\Filament\Imports\SupplierProductImporter;
use App\Filament\Resources\SupplierProducts\SupplierProductResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListSupplierProducts extends ListRecords
{
    protected static string $resource = SupplierProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('CSV-Import')
                ->importer(SupplierProductImporter::class),
            ExportAction::make()
                ->label('CSV-Export')
                ->exporter(SupplierProductExporter::class),
            CreateAction::make()->label('Neues Lieferanten-Produkt'),
        ];
    }
}
