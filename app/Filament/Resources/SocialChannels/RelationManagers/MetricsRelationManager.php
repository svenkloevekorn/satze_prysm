<?php

namespace App\Filament\Resources\SocialChannels\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MetricsRelationManager extends RelationManager
{
    protected static string $relationship = 'metrics';

    protected static ?string $title = 'Monitoring-Snapshots';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('captured_at')
                ->label('Aufgenommen am')
                ->required()
                ->default(now())
                ->displayFormat('d.m.Y'),
            TextInput::make('followers')
                ->label('Follower')
                ->numeric(),
            TextInput::make('posts_count')
                ->label('Posts gesamt')
                ->numeric(),
            TextInput::make('avg_likes')
                ->label('⌀ Likes')
                ->numeric(),
            TextInput::make('avg_comments')
                ->label('⌀ Kommentare')
                ->numeric(),
            TextInput::make('engagement_rate')
                ->label('Engagement-Rate (%)')
                ->numeric()
                ->step(0.01)
                ->suffix('%'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('captured_at')
            ->defaultSort('captured_at', 'desc')
            ->columns([
                TextColumn::make('captured_at')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('followers')
                    ->label('Follower')
                    ->numeric(locale: 'de'),
                TextColumn::make('posts_count')
                    ->label('Posts')
                    ->numeric(),
                TextColumn::make('avg_likes')
                    ->label('⌀ Likes')
                    ->numeric(),
                TextColumn::make('avg_comments')
                    ->label('⌀ Kommentare')
                    ->numeric(),
                TextColumn::make('engagement_rate')
                    ->label('Engagement %')
                    ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 2, ',', '.').' %' : '–'),
            ])
            ->headerActions([
                CreateAction::make()->label('Snapshot hinzufügen'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
