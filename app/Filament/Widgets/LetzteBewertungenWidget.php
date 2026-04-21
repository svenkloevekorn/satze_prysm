<?php

namespace App\Filament\Widgets;

use App\Enums\RatingType;
use App\Models\Rating;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LetzteBewertungenWidget extends BaseWidget
{
    protected static ?string $heading = 'Letzte Bewertungen';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Rating::query()
                    ->with(['ratable', 'dimension', 'user'])
                    ->latest('rated_at')
                    ->limit(5),
            )
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Noch keine Bewertungen')
            ->emptyStateDescription('Öffne ein Produkt und füge eine Bewertung hinzu.')
            ->columns([
                TextColumn::make('ratable.name')
                    ->label('Produkt')
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
                    ->formatStateUsing(fn (int $state) => "{$state}/10"),
                TextColumn::make('user.name')
                    ->label('Von')
                    ->toggleable(),
                TextColumn::make('rated_at')
                    ->label('Am')
                    ->date('d.m.Y'),
            ]);
    }
}
