<?php

namespace App\Filament\Resources\Ratings\Tables;

use App\Enums\RatingType;
use App\Models\CompetitorProduct;
use App\Models\SupplierProduct;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RatingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('rated_at', 'desc')
            ->columns([
                TextColumn::make('ratable_type')
                    ->label('Objekt-Typ')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'competitor_product', CompetitorProduct::class => 'Wettbewerber',
                        'supplier_product', SupplierProduct::class => 'Lieferant',
                        default => class_basename($state),
                    })
                    ->badge()
                    ->color('gray'),
                TextColumn::make('ratable.name')
                    ->label('Produkt')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),
                TextColumn::make('dimension.name')
                    ->label('Dimension')
                    ->badge()
                    ->placeholder('Gesamt'),
                TextColumn::make('type')
                    ->label('Art')
                    ->badge()
                    ->formatStateUsing(fn (RatingType $state) => $state->label())
                    ->color(fn (RatingType $state) => $state->color()),
                TextColumn::make('score')
                    ->label('Score')
                    ->formatStateUsing(fn (int $state) => "{$state}/10")
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Bewertet von')
                    ->toggleable(),
                TextColumn::make('rated_at')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Art')
                    ->options(RatingType::options()),
                SelectFilter::make('ratable_type')
                    ->label('Objekt-Typ')
                    ->options([
                        'competitor_product' => 'Wettbewerbsprodukt',
                        'supplier_product' => 'Lieferanten-Produkt',
                    ]),
                SelectFilter::make('rating_dimension_id')
                    ->label('Dimension')
                    ->relationship('dimension', 'name'),
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
