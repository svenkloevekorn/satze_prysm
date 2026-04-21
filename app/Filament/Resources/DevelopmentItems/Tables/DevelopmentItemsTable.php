<?php

namespace App\Filament\Resources\DevelopmentItems\Tables;

use App\Enums\DevelopmentStatus;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class DevelopmentItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
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
                    BulkAction::make('changeStatus')
                        ->label('Status ändern')
                        ->icon('heroicon-o-arrow-path')
                        ->schema([
                            Select::make('status')
                                ->label('Neuer Status')
                                ->options(DevelopmentStatus::options())
                                ->required(),
                        ])
                        ->action(function (array $data, Collection $records) {
                            $records->each(fn ($record) => $record->update(['status' => $data['status']]));

                            Notification::make()
                                ->title($records->count().' Items aktualisiert')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
