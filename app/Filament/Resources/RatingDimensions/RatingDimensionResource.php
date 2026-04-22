<?php

namespace App\Filament\Resources\RatingDimensions;

use App\Filament\Resources\RatingDimensions\Pages\CreateRatingDimension;
use App\Filament\Resources\RatingDimensions\Pages\EditRatingDimension;
use App\Filament\Resources\RatingDimensions\Pages\ListRatingDimensions;
use App\Filament\Resources\RatingDimensions\Schemas\RatingDimensionForm;
use App\Filament\Resources\RatingDimensions\Tables\RatingDimensionsTable;
use App\Models\RatingDimension;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RatingDimensionResource extends Resource
{
    protected static ?string $model = RatingDimension::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Bewertungs-Dimensionen';

    protected static ?string $modelLabel = 'Bewertungs-Dimension';

    protected static ?string $pluralModelLabel = 'Bewertungs-Dimensionen';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RatingDimensionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RatingDimensionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRatingDimensions::route('/'),
            'create' => CreateRatingDimension::route('/create'),
            'edit' => EditRatingDimension::route('/{record}/edit'),
        ];
    }
}
