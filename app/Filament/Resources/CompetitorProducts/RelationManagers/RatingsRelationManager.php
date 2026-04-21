<?php

namespace App\Filament\Resources\CompetitorProducts\RelationManagers;

use App\Enums\RatingType;
use App\Filament\Resources\Ratings\Schemas\RatingForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RatingsRelationManager extends RelationManager
{
    protected static string $relationship = 'ratings';

    protected static ?string $title = 'Bewertungen';

    protected static ?string $modelLabel = 'Bewertung';

    protected static ?string $pluralModelLabel = 'Bewertungen';

    public function form(Schema $schema): Schema
    {
        return RatingForm::configure($schema, inRelationManager: true);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('score')
            ->defaultSort('rated_at', 'desc')
            ->columns([
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
                    ->formatStateUsing(fn (int $state) => "{$state}/10")
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Kommentar')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->comment),
                TextColumn::make('rated_at')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Art')
                    ->options(RatingType::options()),
                SelectFilter::make('rating_dimension_id')
                    ->label('Dimension')
                    ->relationship('dimension', 'name'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Bewertung hinzufügen')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['user_id'] ??= auth()->id();

                        return $data;
                    }),
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
