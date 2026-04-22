<?php

namespace App\Filament\Resources\Ratings\Schemas;

use App\Enums\RatingSource;
use App\Models\CompetitorProduct;
use App\Models\FinalProduct;
use App\Models\SupplierProduct;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RatingForm
{
    public static function configure(Schema $schema, bool $inRelationManager = false): Schema
    {
        $fields = [];

        if (! $inRelationManager) {
            $fields[] = Section::make('Bewertet wird')
                ->columns(2)
                ->schema([
                    Select::make('ratable_type')
                        ->label('Objekt-Typ')
                        ->options([
                            'competitor_product' => 'Wettbewerbsprodukt',
                            'supplier_product' => 'Lieferanten-Produkt',
                            'final_product' => 'Finales Produkt',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($set) => $set('ratable_id', null)),
                    Select::make('ratable_id')
                        ->label('Objekt')
                        ->options(function ($get) {
                            return match ($get('ratable_type')) {
                                'competitor_product' => CompetitorProduct::query()->pluck('name', 'id')->toArray(),
                                'supplier_product' => SupplierProduct::query()->pluck('name', 'id')->toArray(),
                                'final_product' => FinalProduct::query()->pluck('name', 'id')->toArray(),
                                default => [],
                            };
                        })
                        ->searchable()
                        ->required(),
                ]);
        }

        $fields[] = Section::make('Bewertung')
            ->schema([
                Select::make('sources')
                    ->label('Quelle(n) der Bewertung')
                    ->multiple()
                    ->options(RatingSource::options())
                    ->required()
                    ->helperText('Woher kommt diese Einschätzung? Mehrfach-Auswahl möglich.')
                    ->columnSpanFull(),
                Grid::make(2)->schema([
                    Select::make('rating_dimension_id')
                        ->label('Dimension')
                        ->relationship('dimension', 'name', fn ($query) => $query->where('is_active', true))
                        ->searchable()
                        ->preload()
                        ->placeholder('Gesamt-Bewertung (ohne Dimension)')
                        ->helperText('Leer lassen für eine Gesamt-Bewertung'),
                    TextInput::make('score')
                        ->label('Score (1-10)')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(10)
                        ->suffix('/10'),
                ]),
                DatePicker::make('rated_at')
                    ->label('Bewertet am')
                    ->default(now())
                    ->displayFormat('d.m.Y'),
                Textarea::make('comment')
                    ->label('Kommentar')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('positives')
                    ->label('👍 Positive Punkte')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('negatives')
                    ->label('👎 Negative Punkte')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);

        return $schema->components($fields);
    }
}
