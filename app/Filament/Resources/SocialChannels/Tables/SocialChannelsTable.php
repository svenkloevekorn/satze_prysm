<?php

namespace App\Filament\Resources\SocialChannels\Tables;

use App\Enums\SocialPlatform;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class SocialChannelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('followers', 'desc')
            ->groups([
                Group::make('platform')->label('Plattform')->collapsible(),
                Group::make('owner_type')->label('Besitzer-Typ')->collapsible(),
            ])
            ->emptyStateHeading('Noch keine Kanäle')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right')
            ->columns([
                TextColumn::make('platform')
                    ->label('Plattform')
                    ->badge()
                    ->formatStateUsing(fn (SocialPlatform $state) => $state->label())
                    ->color(fn (SocialPlatform $state) => $state->color())
                    ->icon(fn (SocialPlatform $state) => $state->icon())
                    ->sortable(),
                TextColumn::make('owner.name')
                    ->label('Besitzer')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('handle')
                    ->label('Handle')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('followers')
                    ->label('Follower')
                    ->numeric(locale: 'de')
                    ->sortable(),
                TextColumn::make('engagement_rate')
                    ->label('Engagement')
                    ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 2, ',', '.').' %' : '–')
                    ->sortable(),
                TextColumn::make('country')
                    ->label('Land')
                    ->badge()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('platform')
                    ->label('Plattform')
                    ->options(SocialPlatform::options()),
                SelectFilter::make('owner_type')
                    ->label('Besitzer-Typ')
                    ->options([
                        'influencer' => 'Influencer',
                        'brand' => 'Marke',
                    ]),
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
