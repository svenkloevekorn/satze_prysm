<?php

namespace App\Filament\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CompetitorProduct;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class CompetitorProductImporter extends Importer
{
    protected static ?string $model = CompetitorProduct::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Produktname')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('brand')
                ->label('Marke (Name)')
                ->fillRecordUsing(function (CompetitorProduct $record, ?string $state) {
                    if (filled($state)) {
                        $brand = Brand::firstOrCreate(['name' => trim($state)], ['is_active' => true]);
                        $record->brand_id = $brand->id;
                    }
                }),
            ImportColumn::make('category')
                ->label('Kategorie (Name)')
                ->fillRecordUsing(function (CompetitorProduct $record, ?string $state) {
                    if (filled($state)) {
                        $category = Category::firstOrCreate(
                            ['slug' => Str::slug($state)],
                            ['name' => trim($state), 'is_active' => true],
                        );
                        $record->category_id = $category->id;
                    }
                }),
            ImportColumn::make('description')
                ->label('Beschreibung'),
            ImportColumn::make('materials')
                ->label('Materialien (komma-getrennt)')
                ->castStateUsing(fn (?string $state) => $state ? array_map('trim', explode(',', $state)) : null),
            ImportColumn::make('colors')
                ->label('Farben (komma-getrennt)')
                ->castStateUsing(fn (?string $state) => $state ? array_map('trim', explode(',', $state)) : null),
            ImportColumn::make('sizes')
                ->label('Größen (komma-getrennt)')
                ->castStateUsing(fn (?string $state) => $state ? array_map('trim', explode(',', $state)) : null),
            ImportColumn::make('price_min')
                ->label('Mindestpreis')
                ->numeric()
                ->rules(['nullable', 'numeric']),
            ImportColumn::make('price_max')
                ->label('Höchstpreis')
                ->numeric()
                ->rules(['nullable', 'numeric']),
        ];
    }

    public function resolveRecord(): CompetitorProduct
    {
        return new CompetitorProduct;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import abgeschlossen: '.Number::format($import->successful_rows).' Zeile(n) erfolgreich importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' Zeile(n) fehlgeschlagen.';
        }

        return $body;
    }
}
