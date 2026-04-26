<?php

namespace App\Filament\Exports;

use App\Models\CompetitorProduct;
use App\Support\CsvSafe;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class CompetitorProductExporter extends Exporter
{
    protected static ?string $model = CompetitorProduct::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')->label('Produktname')
                ->formatStateUsing(fn ($state) => CsvSafe::value($state)),
            ExportColumn::make('brand.name')->label('Marke')
                ->formatStateUsing(fn ($state) => CsvSafe::value($state)),
            ExportColumn::make('category.name')->label('Kategorie')
                ->formatStateUsing(fn ($state) => CsvSafe::value($state)),
            ExportColumn::make('description')->label('Beschreibung')
                ->formatStateUsing(fn ($state) => CsvSafe::value($state)),
            ExportColumn::make('materials')
                ->label('Materialien')
                ->state(fn ($record) => CsvSafe::value(is_array($record->materials) ? implode(', ', $record->materials) : $record->materials)),
            ExportColumn::make('colors')
                ->label('Farben')
                ->state(fn ($record) => CsvSafe::value(is_array($record->colors) ? implode(', ', $record->colors) : $record->colors)),
            ExportColumn::make('sizes')
                ->label('Größen')
                ->state(fn ($record) => CsvSafe::value(is_array($record->sizes) ? implode(', ', $record->sizes) : $record->sizes)),
            ExportColumn::make('price_min')->label('Mindestpreis'),
            ExportColumn::make('price_max')->label('Höchstpreis'),
            ExportColumn::make('ratings_avg_score')
                ->label('⌀ Bewertung (1-10)')
                ->state(fn ($record) => $record->averageScore()),
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
