<?php

namespace App\Filament\Resources\Suppliers\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Produkte des Lieferanten';

    protected static ?string $modelLabel = 'Lieferanten-Produkt';

    protected static ?string $pluralModelLabel = 'Lieferanten-Produkte';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Produktname')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label('Kategorie')
                    ->relationship('category', 'name', fn ($query) => $query->with('parent'))
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->fullName())
                    ->searchable(['name'])
                    ->preload(),
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
                    ->label('Mindestabnahme (MOQ)')
                    ->numeric()
                    ->minValue(1)
                    ->suffix('Stück'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Produkt')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategorie')
                    ->badge(),
                TextColumn::make('purchase_price')
                    ->label('EK')
                    ->money('EUR'),
                TextColumn::make('recommended_retail_price')
                    ->label('VK (empf.)')
                    ->money('EUR'),
                TextColumn::make('moq')
                    ->label('MOQ')
                    ->numeric(),
            ])
            ->headerActions([
                CreateAction::make()->label('Produkt hinzufügen'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
