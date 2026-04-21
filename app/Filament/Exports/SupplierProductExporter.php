<?php

namespace App\Filament\Exports;

use App\Models\SupplierProduct;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class SupplierProductExporter extends Exporter
{
    protected static ?string $model = SupplierProduct::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')->label('Produktname'),
            ExportColumn::make('supplier.name')->label('Lieferant'),
            ExportColumn::make('category.name')->label('Kategorie'),
            ExportColumn::make('description')->label('Beschreibung'),
            ExportColumn::make('purchase_price')->label('Einkaufspreis'),
            ExportColumn::make('recommended_retail_price')->label('VK empfohlen'),
            ExportColumn::make('moq')->label('MOQ'),
            ExportColumn::make('materials')
                ->label('Materialien')
                ->state(fn ($record) => is_array($record->materials) ? implode(', ', $record->materials) : $record->materials),
            ExportColumn::make('colors')
                ->label('Farben')
                ->state(fn ($record) => is_array($record->colors) ? implode(', ', $record->colors) : $record->colors),
            ExportColumn::make('sizes')
                ->label('Größen')
                ->state(fn ($record) => is_array($record->sizes) ? implode(', ', $record->sizes) : $record->sizes),
            ExportColumn::make('notes')->label('Notizen'),
            ExportColumn::make('created_at')->label('Erstellt am'),
            ExportColumn::make('updated_at')->label('Geändert am'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export abgeschlossen: '.Number::format($export->successful_rows).' Zeile(n) exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' Zeile(n) fehlgeschlagen.';
        }

        return $body;
    }
}
