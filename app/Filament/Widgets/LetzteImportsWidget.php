<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Actions\Imports\Models\Import;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\URL;

class LetzteImportsWidget extends BaseWidget
{
    protected static ?string $heading = 'Letzte CSV-Imports';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 6;

    private const IMPORTER_LABELS = [
        'App\\Filament\\Imports\\CompetitorProductImporter' => 'Wettbewerbsprodukte',
        'App\\Filament\\Imports\\SupplierProductImporter' => 'Lieferanten-Produkte',
        'App\\Filament\\Imports\\ChannelMetricImporter' => 'Channel-Metriken',
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Import::query()->latest('id')->limit(5),
            )
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Noch keine Imports')
            ->emptyStateDescription('Sobald jemand eine CSV importiert, erscheint sie hier.')
            ->emptyStateIcon('heroicon-o-arrow-up-tray')
            ->columns([
                TextColumn::make('file_name')
                    ->label('Datei')
                    ->weight('bold')
                    ->limit(40),
                TextColumn::make('importer')
                    ->label('Typ')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state) => self::IMPORTER_LABELS[$state] ?? class_basename($state)),
                TextColumn::make('user.name')
                    ->label('Von')
                    ->placeholder('–'),
                TextColumn::make('successful_rows')
                    ->label('Erfolgreich')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state, $record) => $state.' / '.$record->total_rows),
                TextColumn::make('failed_rows_count')
                    ->label('Fehler')
                    ->state(fn ($record) => $record->total_rows - $record->successful_rows)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),
                TextColumn::make('completed_at')
                    ->label('Abgeschlossen')
                    ->since()
                    ->placeholder('läuft …'),
            ])
            ->recordActions([
                Action::make('downloadFailures')
                    ->label('Fehler-CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('danger')
                    ->visible(fn (Import $record) => ($record->total_rows - $record->successful_rows) > 0)
                    ->url(fn (Import $record) => URL::signedRoute(
                        'filament.imports.failed-rows.download',
                        ['authGuard' => 'web', 'import' => $record],
                        absolute: false,
                    ), shouldOpenInNewTab: true),
            ]);
    }
}
