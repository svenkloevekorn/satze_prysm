<?php

namespace App\Filament\Actions;

use App\Enums\RatingSource;
use App\Models\Rating;
use App\Models\RatingDimension;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;

/**
 * Action "Alle Dimensionen bewerten": öffnet ein Modal mit einem
 * Repeater, der für jede aktive Dimension eine Zeile vorbereitet.
 * Aus einer Submit-Runde werden so mehrere Rating-Zeilen angelegt.
 */
class CreateMultiRatingAction
{
    public static function make(string $ratableType, callable $getRatableId): Action
    {
        return Action::make('createMultiRating')
            ->label('Alle Dimensionen bewerten')
            ->icon('heroicon-o-squares-plus')
            ->color('primary')
            ->modalHeading('Mehrere Dimensionen auf einmal bewerten')
            ->modalDescription('Eine Zeile pro Dimension – leere Scores werden ignoriert.')
            ->modalSubmitActionLabel('Alle speichern')
            ->schema([
                Section::make('Gemeinsame Angaben')
                    ->columns(2)
                    ->schema([
                        Select::make('sources')
                            ->label('Quelle(n)')
                            ->multiple()
                            ->options(RatingSource::options())
                            ->required()
                            ->columnSpanFull(),
                        DatePicker::make('rated_at')
                            ->label('Bewertet am')
                            ->default(now())
                            ->required()
                            ->displayFormat('d.m.Y'),
                    ]),
                Repeater::make('ratings')
                    ->label('Bewertungen pro Dimension')
                    ->default(fn () => RatingDimension::where('is_active', true)
                        ->orderBy('sort_order')
                        ->get()
                        ->map(fn ($dim) => [
                            'rating_dimension_id' => $dim->id,
                            'score' => null,
                            'comment' => null,
                        ])
                        ->toArray())
                    ->schema([
                        Select::make('rating_dimension_id')
                            ->label('Dimension')
                            ->options(fn () => RatingDimension::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('score')
                            ->label('Score (1-10)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->suffix('/10'),
                        Textarea::make('comment')
                            ->label('Kommentar (optional)')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->columnSpanFull(),
            ])
            ->action(function (array $data) use ($ratableType, $getRatableId) {
                $ratableId = $getRatableId();
                $count = 0;

                foreach ($data['ratings'] as $row) {
                    if (blank($row['score'] ?? null)) {
                        continue; // leere Zeilen überspringen
                    }

                    Rating::create([
                        'ratable_type' => $ratableType,
                        'ratable_id' => $ratableId,
                        'rating_dimension_id' => $row['rating_dimension_id'],
                        'user_id' => auth()->id(),
                        'sources' => $data['sources'],
                        'score' => (int) $row['score'],
                        'comment' => $row['comment'] ?? null,
                        'rated_at' => $data['rated_at'],
                    ]);

                    $count++;
                }

                Notification::make()
                    ->title("{$count} Bewertungen angelegt")
                    ->success()
                    ->send();
            });
    }
}
