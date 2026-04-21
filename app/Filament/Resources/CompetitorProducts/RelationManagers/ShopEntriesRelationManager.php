<?php

namespace App\Filament\Resources\CompetitorProducts\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShopEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'shopEntries';

    protected static ?string $title = 'Shop-Einträge';

    protected static ?string $modelLabel = 'Shop-Eintrag';

    protected static ?string $pluralModelLabel = 'Shop-Einträge';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('shop_id')
                    ->label('Shop')
                    ->relationship('shop', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('product_url')
                    ->label('Produkt-URL im Shop')
                    ->url()
                    ->maxLength(500)
                    ->columnSpanFull(),
                TextInput::make('observed_price')
                    ->label('Beobachteter Preis')
                    ->numeric()
                    ->step(0.01)
                    ->prefix('€'),
                DatePicker::make('observed_at')
                    ->label('Beobachtet am')
                    ->default(now())
                    ->displayFormat('d.m.Y'),
                Textarea::make('notes')
                    ->label('Notiz')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('shop.name')
            ->defaultSort('observed_at', 'desc')
            ->columns([
                TextColumn::make('shop.name')
                    ->label('Shop')
                    ->badge()
                    ->searchable(),
                TextColumn::make('observed_price')
                    ->label('Preis')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('observed_at')
                    ->label('Beobachtet am')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('product_url')
                    ->label('Link')
                    ->url(fn ($record) => $record->product_url)
                    ->openUrlInNewTab()
                    ->limit(30),
                TextColumn::make('notes')
                    ->label('Notiz')
                    ->limit(40)
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Shop-Eintrag hinzufügen'),
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
