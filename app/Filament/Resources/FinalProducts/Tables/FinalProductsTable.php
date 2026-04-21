<?php

namespace App\Filament\Resources\FinalProducts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FinalProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('launched_at', 'desc')
            ->emptyStateHeading('Noch keine finalen Produkte')
            ->emptyStateDescription('Sobald ein Entwicklungs-Item Status „Final" erreicht, erscheint es hier automatisch.')
            ->emptyStateIcon('heroicon-o-trophy')
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
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->placeholder('–'),
                TextColumn::make('category.name')
                    ->label('Kategorie')
                    ->badge()
                    ->searchable(),
                TextColumn::make('cost_price')
                    ->label('EK')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('retail_price')
                    ->label('VK')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('margin')
                    ->label('Marge %')
                    ->state(function ($record) {
                        if (! $record->cost_price || ! $record->retail_price) {
                            return null;
                        }
                        $margin = (($record->retail_price - $record->cost_price) / $record->retail_price) * 100;

                        return round($margin, 1).'%';
                    }),
                TextColumn::make('launched_at')
                    ->label('Launch')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('ratings_avg_score')
                    ->label('⌀ Bewertung')
                    ->avg('ratings', 'score')
                    ->formatStateUsing(fn ($state) => $state ? round($state, 1).'/10' : '–')
                    ->toggleable(),
            ])
            ->filters([
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
