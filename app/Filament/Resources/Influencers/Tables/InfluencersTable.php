<?php

namespace App\Filament\Resources\Influencers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InfluencersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->emptyStateHeading('Noch keine Influencer')
            ->emptyStateIcon('heroicon-o-user-circle')
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('')
                    ->collection('avatar')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('country')
                    ->label('Land')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('channels_count')
                    ->label('Kanäle')
                    ->counts('channels')
                    ->badge()
                    ->color('info'),
                TextColumn::make('email')
                    ->label('E-Mail')
                    ->copyable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Aktiv-Status'),
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
