<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CompetitorProducts\CompetitorProductResource;
use App\Models\CompetitorProduct;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UnbewerteteProdukteWidget extends BaseWidget
{
    protected static ?string $heading = 'Unbewertete Wettbewerbsprodukte';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CompetitorProduct::query()
                    ->doesntHave('ratings')
                    ->latest()
                    ->limit(5),
            )
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Alles bewertet!')
            ->emptyStateDescription('Alle Wettbewerbsprodukte haben mindestens eine Bewertung.')
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
                TextColumn::make('price_min')
                    ->label('ab')
                    ->money('EUR'),
            ]);
    }
}
