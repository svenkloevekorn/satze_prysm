<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CompetitorProducts\CompetitorProductResource;
use App\Models\CompetitorProduct;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LetzteAenderungenWidget extends BaseWidget
{
    protected static ?string $heading = 'Letzte Änderungen (Wettbewerbsprodukte)';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CompetitorProduct::query()
                    ->latest('updated_at')
                    ->limit(5),
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('name')
                    ->label('Produkt')
                    ->weight('bold')
                    ->url(fn ($record) => CompetitorProductResource::getUrl('edit', ['record' => $record])),
                TextColumn::make('brand.name')
                    ->label('Marke')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('category.name')
                    ->label('Kategorie')
                    ->badge(),
                TextColumn::make('updated_at')
                    ->label('Zuletzt geändert')
                    ->since(),
            ]);
    }
}
