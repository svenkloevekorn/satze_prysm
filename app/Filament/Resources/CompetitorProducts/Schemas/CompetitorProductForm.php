<?php

namespace App\Filament\Resources\CompetitorProducts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
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
                                SpatieTagsInput::make('tags')
                                    ->label('Tags (frei)')
                                    ->placeholder('z.B. Best-Seller, Neu, Trend')
                                    ->columnSpanFull(),
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
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->prefix('€'),
                                        TextInput::make('price_max')
                                            ->label('Höchstpreis (€)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->prefix('€'),
                                    ]),
                            ]),

                        Tab::make('Nachhaltigkeit')
                            ->icon('heroicon-o-globe-europe-africa')
                            ->schema([
                                Section::make('Nachhaltigkeits-Kennzahlen')
                                    ->description('Diese Daten helfen bei der Vergleichbarkeit und Bewertung von Wettbewerbsprodukten.')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('co2_kg')
                                            ->label('CO₂-Fußabdruck')
                                            ->numeric()
                                            ->step(0.01)
                                            ->suffix('kg CO₂e')
                                            ->helperText('Falls bekannt (z.B. aus Label, Studie, Herstellerangabe).'),
                                        TextInput::make('recycled_content_pct')
                                            ->label('Recycling-Anteil')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%'),
                                        Select::make('certifications')
                                            ->label('Zertifikate')
                                            ->multiple()
                                            ->options([
                                                'fair_trade' => 'Fair Trade',
                                                'bluesign' => 'Bluesign',
                                                'gots' => 'GOTS',
                                                'oeko_tex' => 'OEKO-TEX',
                                                'bsci' => 'BSCI',
                                                'grs' => 'GRS (Global Recycled Standard)',
                                                'pfc_free' => 'PFC-frei',
                                                'climate_neutral' => 'Klimaneutral',
                                            ])
                                            ->searchable()
                                            ->columnSpanFull(),
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
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->maxFiles(10)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
