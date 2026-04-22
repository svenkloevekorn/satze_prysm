<?php

namespace App\Filament\Resources\QualityCriteria;

use App\Filament\Resources\QualityCriteria\Pages\CreateQualityCriterion;
use App\Filament\Resources\QualityCriteria\Pages\EditQualityCriterion;
use App\Filament\Resources\QualityCriteria\Pages\ListQualityCriteria;
use App\Filament\Resources\QualityCriteria\Schemas\QualityCriterionForm;
use App\Filament\Resources\QualityCriteria\Tables\QualityCriteriaTable;
use App\Models\QualityCriterion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QualityCriterionResource extends Resource
{
    protected static ?string $model = QualityCriterion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?string $navigationLabel = 'Qualitätskriterien';

    protected static ?string $modelLabel = 'Qualitätskriterium';

    protected static ?string $pluralModelLabel = 'Qualitätskriterien';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return QualityCriterionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QualityCriteriaTable::configure($table);
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
            'index' => ListQualityCriteria::route('/'),
            'create' => CreateQualityCriterion::route('/create'),
            'edit' => EditQualityCriterion::route('/{record}/edit'),
        ];
    }
}
