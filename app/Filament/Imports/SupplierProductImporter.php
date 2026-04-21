<?php

namespace App\Filament\Imports;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class SupplierProductImporter extends Importer
{
    protected static ?string $model = SupplierProduct::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Produktname')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('supplier')
                ->label('Lieferant (Name)')
                ->requiredMapping()
                ->fillRecordUsing(function (SupplierProduct $record, ?string $state) {
                    if (filled($state)) {
                        $supplier = Supplier::firstOrCreate(['name' => trim($state)], ['is_active' => true]);
                        $record->supplier_id = $supplier->id;
                    }
                }),
            ImportColumn::make('category')
                ->label('Kategorie (Name)')
                ->fillRecordUsing(function (SupplierProduct $record, ?string $state) {
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
            ImportColumn::make('purchase_price')
                ->label('Einkaufspreis')
                ->numeric()
                ->rules(['nullable', 'numeric']),
            ImportColumn::make('recommended_retail_price')
                ->label('VK-Preis (empf.)')
                ->numeric()
                ->rules(['nullable', 'numeric']),
            ImportColumn::make('moq')
                ->label('MOQ (Stück)')
                ->integer()
                ->rules(['nullable', 'integer', 'min:1']),
            ImportColumn::make('materials')
                ->label('Materialien (komma-getrennt)')
                ->castStateUsing(fn (?string $state) => $state ? array_map('trim', explode(',', $state)) : null),
            ImportColumn::make('colors')
                ->label('Farben (komma-getrennt)')
                ->castStateUsing(fn (?string $state) => $state ? array_map('trim', explode(',', $state)) : null),
            ImportColumn::make('sizes')
                ->label('Größen (komma-getrennt)')
                ->castStateUsing(fn (?string $state) => $state ? array_map('trim', explode(',', $state)) : null),
            ImportColumn::make('notes')
                ->label('Notizen'),
        ];
    }

    public function resolveRecord(): SupplierProduct
    {
        return new SupplierProduct;
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
