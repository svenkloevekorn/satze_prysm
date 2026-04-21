<?php

namespace App\Filament\Resources\SupplierProducts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class SupplierProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Lieferanten-Produkt')
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
                                        Select::make('supplier_id')
                                            ->label('Lieferant')
                                            ->relationship('supplier', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Select::make('category_id')
                                            ->label('Kategorie')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload(),
                                        Textarea::make('description')
                                            ->label('Beschreibung')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Preis & Konditionen')
                            ->icon('heroicon-o-currency-euro')
                            ->schema([
                                Section::make('Konditionen')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('purchase_price')
                                            ->label('Einkaufspreis (€)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->prefix('€'),
                                        TextInput::make('recommended_retail_price')
                                            ->label('Empfohlener VK-Preis (€)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->prefix('€'),
                                        TextInput::make('moq')
                                            ->label('MOQ (Mindestabnahme)')
                                            ->numeric()
                                            ->minValue(1)
                                            ->suffix('Stück')
                                            ->helperText('Minimum Order Quantity'),
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
                                Textarea::make('notes')
                                    ->label('Notizen')
                                    ->rows(3)
                                    ->columnSpanFull(),
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
