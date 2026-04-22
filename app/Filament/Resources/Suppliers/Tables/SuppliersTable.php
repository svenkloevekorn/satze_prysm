<?php

namespace App\Filament\Resources\Suppliers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->emptyStateHeading('Noch keine Lieferanten')
            ->emptyStateDescription('Leg den ersten Lieferanten an, danach kannst du Kontakte und Produkte hinzufügen.')
            ->emptyStateIcon('heroicon-o-building-office')
            ->columns([
                TextColumn::make('name')
                    ->label('Firmenname')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('country')
                    ->label('Land')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('rating')
                    ->label('Bewertung')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}/10" : '–')
                    ->sortable(),
                TextColumn::make('contacts_count')
                    ->label('Kontakte')
                    ->counts('contacts')
                    ->badge()
                    ->color('info'),
                TextColumn::make('products_count')
                    ->label('Produkte')
                    ->counts('products')
                    ->badge()
                    ->color('success'),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                TextColumn::make('sustainability_score')
                    ->label('Nachhaltigkeit')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}/10" : '–')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Geändert')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktiv-Status'),
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
