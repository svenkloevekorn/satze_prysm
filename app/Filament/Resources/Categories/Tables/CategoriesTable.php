<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->label('Oberkategorie')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight(fn ($record) => $record->parent_id === null ? 'bold' : 'normal')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->parent_id === null
                            ? $state
                            : '↳ '.$state;
                    }),
                TextColumn::make('children_count')
                    ->label('Unterkat.')
                    ->counts('children')
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Geändert')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Oberkategorie')
                    ->options(fn () => Category::whereNull('parent_id')->pluck('name', 'id'))
                    ->placeholder('Alle'),
                TernaryFilter::make('top_level')
                    ->label('Ebene')
                    ->placeholder('Alle Ebenen')
                    ->trueLabel('Nur Oberkategorien')
                    ->falseLabel('Nur Unterkategorien')
                    ->queries(
                        true: fn ($query) => $query->whereNull('parent_id'),
                        false: fn ($query) => $query->whereNotNull('parent_id'),
                        blank: fn ($query) => $query,
                    ),
                TernaryFilter::make('is_active')
                    ->label('Aktiv-Status')
                    ->placeholder('Alle')
                    ->trueLabel('Nur aktive')
                    ->falseLabel('Nur inaktive'),
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
