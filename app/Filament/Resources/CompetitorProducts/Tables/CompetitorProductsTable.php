<?php

namespace App\Filament\Resources\CompetitorProducts\Tables;

use App\Filament\Actions\BulkUpdateAction;
use App\Models\Brand;
use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class CompetitorProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->groups([
                Group::make('category.name')->label('Kategorie')->collapsible(),
                Group::make('brand.name')->label('Marke / Hersteller')->collapsible(),
            ])
            ->emptyStateHeading('Noch keine Wettbewerbsprodukte')
            ->emptyStateDescription('Leg das erste Produkt an – oder importiere mehrere via CSV.')
            ->emptyStateIcon('heroicon-o-magnifying-glass')
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
                    ->wrap(),
                TextColumn::make('brand.name')
                    ->label('Marke')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Kategorie')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price_min')
                    ->label('Preis von')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('price_max')
                    ->label('Preis bis')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('shop_entries_count')
                    ->label('Shops')
                    ->counts('shopEntries')
                    ->badge(),
                TextColumn::make('ratings_avg_score')
                    ->label('⌀ Bewertung')
                    ->avg('ratings', 'score')
                    ->formatStateUsing(fn ($state) => $state ? round($state, 1).'/10' : '–'),
                TextColumn::make('ratings_count')
                    ->label('# Bew.')
                    ->counts('ratings')
                    ->badge()
                    ->color('info'),
                SpatieTagsColumn::make('tags')
                    ->label('Tags')
                    ->toggleable(),
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
                SelectFilter::make('brand')
                    ->label('Marke')
                    ->relationship('brand', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                SelectFilter::make('category')
                    ->label('Kategorie')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload(),
                Filter::make('price_range')
                    ->label('Preis')
                    ->schema([
                        TextInput::make('price_from')->label('von €')->numeric(),
                        TextInput::make('price_to')->label('bis €')->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['price_from'] ?? null, fn ($q, $v) => $q->where('price_min', '>=', $v))
                            ->when($data['price_to'] ?? null, fn ($q, $v) => $q->where('price_max', '<=', $v));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkUpdateAction::singleField(
                        name: 'changeBrand',
                        label: 'Marke setzen',
                        icon: 'heroicon-o-tag',
                        schema: [
                            Select::make('brand_id')
                                ->label('Neue Marke')
                                ->options(fn () => Brand::orderBy('name')->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ],
                        field: 'brand_id',
                        successLabel: 'Produkte aktualisiert',
                    ),
                    BulkUpdateAction::singleField(
                        name: 'changeCategory',
                        label: 'Kategorie setzen',
                        icon: 'heroicon-o-folder',
                        schema: [
                            Select::make('category_id')
                                ->label('Neue Kategorie')
                                ->options(fn () => Category::orderBy('name')->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ],
                        field: 'category_id',
                        successLabel: 'Produkte aktualisiert',
                    ),
                    BulkUpdateAction::make(
                        name: 'changePrice',
                        label: 'Preis ändern',
                        icon: 'heroicon-o-currency-euro',
                        schema: [
                            TextInput::make('price_min')->label('Preis von €')->numeric()->minValue(0),
                            TextInput::make('price_max')->label('Preis bis €')->numeric()->minValue(0),
                        ],
                        update: fn (array $data) => array_filter([
                            'price_min' => $data['price_min'] ?? null,
                            'price_max' => $data['price_max'] ?? null,
                        ], fn ($v) => $v !== null && $v !== ''),
                        successLabel: 'Produkte aktualisiert',
                        emptyWarning: 'Mindestens einen Preis angeben',
                    ),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
