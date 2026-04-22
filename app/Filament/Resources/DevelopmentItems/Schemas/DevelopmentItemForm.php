<?php

namespace App\Filament\Resources\DevelopmentItems\Schemas;

use App\Enums\DevelopmentStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class DevelopmentItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Entwicklungs-Item')
                    ->tabs([
                        Tab::make('Allgemein')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Basisdaten')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Name der Idee / des Entwurfs')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Select::make('category_id')
                                            ->label('Kategorie')
                                            ->relationship('category', 'name', fn ($query) => $query->with('parent'))
                                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->fullName())
                                            ->searchable(['name'])
                                            ->preload()
                                            ->required(),
                                        Select::make('status')
                                            ->label('Status')
                                            ->options(DevelopmentStatus::options())
                                            ->default(DevelopmentStatus::Idea->value)
                                            ->required()
                                            ->native(false),
                                        Select::make('user_id')
                                            ->label('Verantwortlich')
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->default(auth()->id()),
                                        DatePicker::make('deadline')
                                            ->label('Deadline')
                                            ->displayFormat('d.m.Y'),
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
                                            ->splitKeys([',', 'Tab']),
                                        TagsInput::make('colors')
                                            ->label('Farben')
                                            ->splitKeys([',', 'Tab']),
                                        TagsInput::make('sizes')
                                            ->label('Größen')
                                            ->splitKeys([',', 'Tab']),
                                    ]),
                                TextInput::make('target_price')
                                    ->label('Zielpreis (€)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->prefix('€'),
                                Textarea::make('notes')
                                    ->label('Notizen')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Inspiration & Basis')
                            ->icon('heroicon-o-link')
                            ->schema([
                                Section::make('Inspiriert von Wettbewerbsprodukten')
                                    ->description('Welche Wettbewerbsprodukte haben diese Idee inspiriert?')
                                    ->schema([
                                        Select::make('competitorInspirations')
                                            ->label('Wettbewerbsprodukte')
                                            ->multiple()
                                            ->relationship(
                                                'competitorInspirations',
                                                'name',
                                                fn ($query) => $query->select(['competitor_products.id', 'competitor_products.name']),
                                            )
                                            ->preload()
                                            ->searchable()
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Basiert auf Lieferanten-Produkten')
                                    ->description('Welche Lieferanten-Produkte sind die technische Basis?')
                                    ->schema([
                                        Select::make('supplierBasis')
                                            ->label('Lieferanten-Produkte')
                                            ->multiple()
                                            ->relationship(
                                                'supplierBasis',
                                                'name',
                                                fn ($query) => $query->select(['supplier_products.id', 'supplier_products.name']),
                                            )
                                            ->preload()
                                            ->searchable()
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Nachhaltigkeit')
                            ->icon('heroicon-o-globe-europe-africa')
                            ->schema([
                                Section::make('Nachhaltigkeits-Ziele')
                                    ->description('Plane schon in der Entwicklung, welche Nachhaltigkeits-Eigenschaften dein Produkt haben soll.')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('co2_kg')
                                            ->label('CO₂-Ziel')
                                            ->numeric()
                                            ->step(0.01)
                                            ->suffix('kg CO₂e'),
                                        TextInput::make('recycled_content_pct')
                                            ->label('Recycling-Anteil (Ziel)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%'),
                                        Select::make('certifications')
                                            ->label('Angestrebte Zertifikate')
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
                        Tab::make('Bilder & Skizzen')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('images')
                                    ->label('Bilder, Skizzen, Tech Sheets')
                                    ->collection('images')
                                    ->multiple()
                                    ->reorderable()
                                    ->image()
                                    ->imageEditor()
                                    ->maxFiles(15)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
