<?php

namespace App\Filament\Resources\SupplierProducts\Tables;

use App\Models\Category;
use App\Models\Supplier;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class SupplierProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->groups([
                Group::make('category.name')->label('Kategorie')->collapsible(),
                Group::make('supplier.name')->label('Lieferant')->collapsible(),
            ])
            ->emptyStateHeading('Noch keine Lieferanten-Produkte')
            ->emptyStateDescription('Leg ein Produkt an oder importiere eine CSV-Liste.')
            ->emptyStateIcon('heroicon-o-cube')
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
                TextColumn::make('co2_kg')
                    ->label('CO₂')
                    ->suffix(' kg')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('recycled_content_pct')
                    ->label('Recycled')
                    ->suffix(' %')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    BulkAction::make('changeSupplier')
                        ->label('Lieferant ändern')
                        ->icon('heroicon-o-truck')
                        ->schema([
                            Select::make('supplier_id')
                                ->label('Neuer Lieferant')
                                ->options(fn () => Supplier::orderBy('name')->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (array $data, Collection $records) {
                            $records->each(fn ($record) => $record->update(['supplier_id' => $data['supplier_id']]));

                            Notification::make()
                                ->title($records->count().' Produkte aktualisiert')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('changeCategory')
                        ->label('Kategorie setzen')
                        ->icon('heroicon-o-folder')
                        ->schema([
                            Select::make('category_id')
                                ->label('Neue Kategorie')
                                ->options(fn () => Category::orderBy('name')->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (array $data, Collection $records) {
                            $records->each(fn ($record) => $record->update(['category_id' => $data['category_id']]));

                            Notification::make()
                                ->title($records->count().' Produkte aktualisiert')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('changePurchasePrice')
                        ->label('EK ändern')
                        ->icon('heroicon-o-currency-euro')
                        ->schema([
                            TextInput::make('purchase_price')
                                ->label('Neuer Einkaufspreis €')
                                ->numeric()
                                ->minValue(0)
                                ->required(),
                        ])
                        ->action(function (array $data, Collection $records) {
                            $records->each(fn ($record) => $record->update(['purchase_price' => $data['purchase_price']]));

                            Notification::make()
                                ->title($records->count().' Produkte aktualisiert')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('changeMoq')
                        ->label('MOQ setzen')
                        ->icon('heroicon-o-cube')
                        ->schema([
                            TextInput::make('moq')
                                ->label('Mindestabnahmemenge')
                                ->integer()
                                ->minValue(1)
                                ->required(),
                        ])
                        ->action(function (array $data, Collection $records) {
                            $records->each(fn ($record) => $record->update(['moq' => $data['moq']]));

                            Notification::make()
                                ->title($records->count().' Produkte aktualisiert')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
