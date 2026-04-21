<?php

namespace App\Filament\Resources\SupplierProducts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SupplierProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumb')
                    ->label('')
                    ->collection('images')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->label('Produkt')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('supplier.name')
                    ->label('Lieferant')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategorie')
                    ->badge()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('purchase_price')
                    ->label('EK')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('recommended_retail_price')
                    ->label('VK empf.')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('moq')
                    ->label('MOQ')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('ratings_avg_score')
                    ->label('⌀ Bewertung')
                    ->avg('ratings', 'score')
                    ->formatStateUsing(fn ($state) => $state ? round($state, 1).'/10' : '–'),
                TextColumn::make('updated_at')
                    ->label('Geändert')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('supplier')
                    ->label('Lieferant')
                    ->relationship('supplier', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                SelectFilter::make('category')
                    ->label('Kategorie')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
