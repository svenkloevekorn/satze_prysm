<?php

namespace App\Filament\Resources\FinalProducts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                            ->relationship('category', 'name', fn ($query) => $query->with('parent'))
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->fullName())
                            ->searchable(['name'])
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
                Section::make('Nachhaltigkeit')
                    ->description('CO₂-Bilanz, Recycling-Anteil und Zertifikate des finalen Produkts.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('co2_kg')
                            ->label('CO₂-Fußabdruck')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('kg CO₂e'),
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
