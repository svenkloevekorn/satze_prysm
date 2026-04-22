<?php

namespace App\Filament\Resources\DevelopmentItems;

use App\Filament\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\DevelopmentItems\Pages\CreateDevelopmentItem;
use App\Filament\Resources\DevelopmentItems\Pages\EditDevelopmentItem;
use App\Filament\Resources\DevelopmentItems\Pages\ListDevelopmentItems;
use App\Filament\Resources\DevelopmentItems\RelationManagers\QualityChecksRelationManager;
use App\Filament\Resources\DevelopmentItems\Schemas\DevelopmentItemForm;
use App\Filament\Resources\DevelopmentItems\Tables\DevelopmentItemsTable;
use App\Models\DevelopmentItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DevelopmentItemResource extends Resource
{
    protected static ?string $model = DevelopmentItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;

    protected static ?string $navigationLabel = 'Entwicklungs-Pipeline';

    protected static ?string $modelLabel = 'Entwicklungs-Item';

    protected static ?string $pluralModelLabel = 'Entwicklungs-Items';

    protected static string|\UnitEnum|null $navigationGroup = 'Produkt-Entwicklung';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'category.name'];
    }

    /**
     * @return array<string, string>
     */
    public static function getGlobalSearchResultDetails($record): array
    {
        $details = [
            'Status' => (string) ($record->status?->label() ?? ''),
            'Kategorie' => (string) ($record->category->name ?? ''),
        ];

        return array_filter($details, static fn (string $v): bool => $v !== '');
    }

    public static function form(Schema $schema): Schema
    {
        return DevelopmentItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevelopmentItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            QualityChecksRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDevelopmentItems::route('/'),
            'create' => CreateDevelopmentItem::route('/create'),
            'edit' => EditDevelopmentItem::route('/{record}/edit'),
        ];
    }
}
