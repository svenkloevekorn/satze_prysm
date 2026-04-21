<?php

namespace App\Filament\Resources\CompetitorProducts;

use App\Filament\Resources\CompetitorProducts\Pages\CreateCompetitorProduct;
use App\Filament\Resources\CompetitorProducts\Pages\EditCompetitorProduct;
use App\Filament\Resources\CompetitorProducts\Pages\ListCompetitorProducts;
use App\Filament\Resources\CompetitorProducts\RelationManagers\RatingsRelationManager;
use App\Filament\Resources\CompetitorProducts\RelationManagers\ShopEntriesRelationManager;
use App\Filament\Resources\CompetitorProducts\Schemas\CompetitorProductForm;
use App\Filament\Resources\CompetitorProducts\Tables\CompetitorProductsTable;
use App\Models\CompetitorProduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompetitorProductResource extends Resource
{
    protected static ?string $model = CompetitorProduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'Wettbewerbsprodukte';

    protected static ?string $modelLabel = 'Wettbewerbsprodukt';

    protected static ?string $pluralModelLabel = 'Wettbewerbsprodukte';

    protected static string|\UnitEnum|null $navigationGroup = 'Marktanalyse';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'brand.name', 'category.name'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Marke' => $record->brand?->name,
            'Kategorie' => $record->category?->name,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return CompetitorProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetitorProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ShopEntriesRelationManager::class,
            RatingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompetitorProducts::route('/'),
            'create' => CreateCompetitorProduct::route('/create'),
            'edit' => EditCompetitorProduct::route('/{record}/edit'),
        ];
    }
}
