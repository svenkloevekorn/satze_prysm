<?php

namespace App\Filament\Resources\CompetitorProducts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class CompetitorProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Wettbewerbsprodukt')
                    ->tabs([
                        Tab::make('Allgemein')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Stammdaten')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Produktname')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Select::make('brand_id')
                                            ->label('Marke')
                                            ->relationship('brand', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('name')->required(),
                                            ]),
                                        Select::make('category_id')
                                            ->label('Kategorie')
                                            ->relationship('category', 'name', fn ($query) => $query->with('parent'))
                                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->fullName())
                                            ->searchable(['name'])
                                            ->preload()
                                            ->required(),
                                        Textarea::make('description')
                                            ->label('Beschreibung')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Eigenschaften')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make('Produkt-Eigenschaften')
                                    ->columns(3)
                                    ->schema([
                                        TagsInput::make('materials')
                                            ->label('Materialien')
                                            ->placeholder('Material eingeben + Enter')
                                            ->splitKeys([',', 'Tab']),
                                        TagsInput::make('colors')
                                            ->label('Farben')
                                            ->placeholder('Farbe eingeben + Enter')
                                            ->splitKeys([',', 'Tab']),
                                        TagsInput::make('sizes')
                                            ->label('Größen')
                                            ->placeholder('Größe eingeben + Enter')
                                            ->splitKeys([',', 'Tab']),
                                    ]),
                            ]),

                        Tab::make('Preis')
                            ->icon('heroicon-o-currency-euro')
                            ->schema([
                                Section::make('Preisrahmen')
                                    ->description('Bewertungen (Score, Stärken, Schwächen) werden unten im Reiter „Bewertungen" gepflegt — mit Dimensionen, Quellen und History.')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('price_min')
                                            ->label('Mindestpreis (€)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->prefix('€'),
                                        TextInput::make('price_max')
                                            ->label('Höchstpreis (€)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->prefix('€'),
                                    ]),
                            ]),

                        Tab::make('Bilder')
                            ->icon('heroicon-o-photo')
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
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
