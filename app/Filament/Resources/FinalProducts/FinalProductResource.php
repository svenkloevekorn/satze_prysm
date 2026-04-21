<?php

namespace App\Filament\Resources\FinalProducts;

use App\Filament\Resources\FinalProducts\Pages\CreateFinalProduct;
use App\Filament\Resources\FinalProducts\Pages\EditFinalProduct;
use App\Filament\Resources\FinalProducts\Pages\ListFinalProducts;
use App\Filament\Resources\FinalProducts\RelationManagers\RatingsRelationManager;
use App\Filament\Resources\FinalProducts\Schemas\FinalProductForm;
use App\Filament\Resources\FinalProducts\Tables\FinalProductsTable;
use App\Models\FinalProduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FinalProductResource extends Resource
{
    protected static ?string $model = FinalProduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $navigationLabel = 'Finale Produkte';

    protected static ?string $modelLabel = 'Finales Produkt';

    protected static ?string $pluralModelLabel = 'Finale Produkte';

    protected static string|\UnitEnum|null $navigationGroup = 'Produkt-Entwicklung';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'category.name'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'SKU' => $record->sku,
            'Kategorie' => $record->category?->name,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return FinalProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinalProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RatingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFinalProducts::route('/'),
            'create' => CreateFinalProduct::route('/create'),
            'edit' => EditFinalProduct::route('/{record}/edit'),
        ];
    }
}
