<?php

namespace App\Filament\Resources\FinalProducts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FinalProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Stammdaten')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Produktname')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('sku')
                            ->label('SKU / Artikelnummer')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('category_id')
                            ->label('Kategorie')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('development_item_id')
                            ->label('Ursprung (Entwicklung)')
                            ->relationship('developmentItem', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Welches Entwicklungs-Item wurde zu diesem Produkt?'),
                        DatePicker::make('launched_at')
                            ->label('Launch-Datum')
                            ->default(now())
                            ->displayFormat('d.m.Y'),
                        Textarea::make('description')
                            ->label('Beschreibung')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Preise')
                    ->columns(2)
                    ->schema([
                        TextInput::make('cost_price')
                            ->label('Selbstkosten (€)')
                            ->numeric()
                            ->step(0.01)
                            ->prefix('€'),
                        TextInput::make('retail_price')
                            ->label('Verkaufspreis (€)')
                            ->numeric()
                            ->step(0.01)
                            ->prefix('€'),
                    ]),
                Section::make('Bilder')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label('Produktbilder')
                            ->collection('images')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->imageEditor()
                            ->maxFiles(10)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
