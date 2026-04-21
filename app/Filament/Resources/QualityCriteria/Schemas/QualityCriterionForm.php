<?php

namespace App\Filament\Resources\QualityCriteria\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QualityCriterionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('z.B. Nahtqualität, Atmungsaktivität'),
                Textarea::make('description')
                    ->label('Beschreibung')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('categories')
                    ->label('Gilt für Kategorien')
                    ->multiple()
                    ->relationship('categories', 'name', fn ($query) => $query->with('parent'))
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->fullName())
                    ->preload()
                    ->searchable(['name'])
                    ->columnSpanFull()
                    ->helperText('Wähle die Produktkategorien, für die dieses Kriterium gilt.'),
                Toggle::make('is_active')
                    ->label('Aktiv')
                    ->default(true),
            ]);
    }
}
