<?php

namespace App\Filament\Widgets;

use App\Enums\DevelopmentStatus;
use App\Filament\Resources\DevelopmentItems\DevelopmentItemResource;
use App\Models\DevelopmentItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OffeneEntwicklungenWidget extends BaseWidget
{
    protected static ?string $heading = 'Offene Entwicklungen';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DevelopmentItem::query()
                    ->whereNot('status', DevelopmentStatus::Final)
                    ->latest('updated_at')
                    ->limit(5),
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->weight('bold')
                    ->url(fn ($record) => DevelopmentItemResource::getUrl('edit', ['record' => $record])),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (DevelopmentStatus $state) => $state->label())
                    ->color(fn (DevelopmentStatus $state) => $state->color())
                    ->icon(fn (DevelopmentStatus $state) => $state->icon()),
                TextColumn::make('category.name')
                    ->label('Kategorie')
                    ->badge(),
                TextColumn::make('deadline')
                    ->label('Deadline')
                    ->date('d.m.Y')
                    ->color(fn ($record) => $record->deadline && $record->deadline->isPast() ? 'danger' : null),
            ]);
    }
}
