<?php

namespace App\Filament\Resources\DevelopmentItems\Tables;

use App\Enums\DevelopmentStatus;
use App\Filament\Actions\BulkUpdateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class DevelopmentItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->groups([
                Group::make('category.name')->label('Kategorie')->collapsible(),
                Group::make('status')->label('Status')->collapsible(),
            ])
            ->emptyStateHeading('Noch keine Entwicklungs-Items')
            ->emptyStateDescription('Starte mit einer ersten Idee – Status „Idee" reicht zum Anfang.')
            ->emptyStateIcon('heroicon-o-light-bulb')
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
                TextColumn::make('co2_kg')
                    ->label('CO₂-Ziel')
                    ->suffix(' kg')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('recycled_content_pct')
                    ->label('Recycled')
                    ->suffix(' %')
                    ->sortable()
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
                    BulkUpdateAction::singleField(
                        name: 'changeStatus',
                        label: 'Status ändern',
                        icon: 'heroicon-o-arrow-path',
                        schema: [
                            Select::make('status')
                                ->label('Neuer Status')
                                ->options(DevelopmentStatus::options())
                                ->required(),
                        ],
                        field: 'status',
                        successLabel: 'Items aktualisiert',
                    ),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
