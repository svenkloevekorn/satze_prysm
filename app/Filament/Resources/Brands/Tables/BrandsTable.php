<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('country')
                    ->label('Land')
                    ->badge()
                    ->placeholder('–')
                    ->toggleable(),
                TextColumn::make('website')
                    ->label('Website')
                    ->url(fn ($record) => $record->website)
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->placeholder('–')
                    ->toggleable(),
                TextColumn::make('social_count')
                    ->label('📱')
                    ->state(function ($record) {
                        $count = collect([$record->instagram, $record->facebook, $record->linkedin, $record->tiktok, $record->youtube])
                            ->filter()
                            ->count();

                        return $count > 0 ? $count : '–';
                    })
                    ->tooltip('Anzahl hinterlegter Social-Media-Kanäle'),
                TextColumn::make('competitor_products_count')
                    ->label('Produkte')
                    ->counts('competitorProducts')
                    ->badge()
                    ->color('info'),
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
                TernaryFilter::make('is_active')
                    ->label('Aktiv-Status'),
                TernaryFilter::make('has_website')
                    ->label('Website')
                    ->placeholder('Alle')
                    ->trueLabel('Mit Website')
                    ->falseLabel('Ohne Website')
                    ->queries(
                        true: fn ($q) => $q->whereNotNull('website'),
                        false: fn ($q) => $q->whereNull('website'),
                        blank: fn ($q) => $q,
                    ),
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
