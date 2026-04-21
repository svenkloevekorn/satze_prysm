<?php

namespace App\Filament\Resources\DevelopmentItems\Tables;

use App\Enums\DevelopmentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DevelopmentItemsTable
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
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (DevelopmentStatus $state) => $state->label())
                    ->color(fn (DevelopmentStatus $state) => $state->color())
                    ->icon(fn (DevelopmentStatus $state) => $state->icon())
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategorie')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label('Verantwortlich')
                    ->toggleable(),
                TextColumn::make('target_price')
                    ->label('Zielpreis')
                    ->money('EUR')
                    ->toggleable(),
                TextColumn::make('deadline')
                    ->label('Deadline')
                    ->date('d.m.Y')
                    ->sortable()
                    ->color(fn ($record) => $record->deadline && $record->deadline->isPast() && ! $record->isFinal() ? 'danger' : null),
                TextColumn::make('final_product.sku')
                    ->label('SKU')
                    ->placeholder('–')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(DevelopmentStatus::options())
                    ->multiple(),
                SelectFilter::make('category')
                    ->label('Kategorie')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('user')
                    ->label('Verantwortlich')
                    ->relationship('user', 'name')
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
