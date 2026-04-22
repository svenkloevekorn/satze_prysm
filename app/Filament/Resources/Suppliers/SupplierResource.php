<?php

namespace App\Filament\Resources\Suppliers;

use App\Filament\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Suppliers\Pages\EditSupplier;
use App\Filament\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\Suppliers\RelationManagers\ContactsRelationManager;
use App\Filament\Resources\Suppliers\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Suppliers\Schemas\SupplierForm;
use App\Filament\Resources\Suppliers\Tables\SuppliersTable;
use App\Models\Supplier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static ?string $navigationLabel = 'Lieferanten';

    protected static ?string $modelLabel = 'Lieferant';

    protected static ?string $pluralModelLabel = 'Lieferanten';

    protected static string|\UnitEnum|null $navigationGroup = 'Lieferanten';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'country'];
    }

    /**
     * @return array<string, string>
     */
    public static function getGlobalSearchResultDetails($record): array
    {
        $details = [
            'Land' => (string) ($record->country ?? ''),
            'Bewertung' => $record->rating ? "{$record->rating}/10" : '',
        ];

        return array_filter($details, static fn (string $v): bool => $v !== '');
    }

    public static function form(Schema $schema): Schema
    {
        return SupplierForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuppliersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ContactsRelationManager::class,
            ProductsRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuppliers::route('/'),
            'create' => CreateSupplier::route('/create'),
            'edit' => EditSupplier::route('/{record}/edit'),
        ];
    }
}
