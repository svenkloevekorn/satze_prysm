<?php

namespace App\Filament\Resources\Ratings\Schemas;

use App\Enums\RatingSource;
use App\Models\Brand;
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
                ->columns(3)
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
                        ->afterStateUpdated(function ($set) {
                            $set('_brand_filter', null);
                            $set('ratable_id', null);
                        }),
                    // Vorfilter Marke – nur bei Wettbewerbsprodukt, wird NICHT gespeichert
                    Select::make('_brand_filter')
                        ->label('Marke (Filter)')
                        ->options(fn () => Brand::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->dehydrated(false)
                        ->visible(fn ($get) => $get('ratable_type') === 'competitor_product')
                        ->afterStateUpdated(fn ($set) => $set('ratable_id', null))
                        ->afterStateHydrated(function ($set, $get, $state) {
                            // Beim Bearbeiten: Marke aus bestehendem Produkt ableiten
                            if ($state || $get('ratable_type') !== 'competitor_product') {
                                return;
                            }
                            if ($id = $get('ratable_id')) {
                                $brandId = CompetitorProduct::whereKey($id)->value('brand_id');
                                if ($brandId) {
                                    $set('_brand_filter', $brandId);
                                }
                            }
                        })
                        ->helperText('Nur Filter – wird nicht gespeichert. Leer = alle Marken.'),
                    Select::make('ratable_id')
                        ->label('Objekt')
                        ->options(function ($get) {
                            return match ($get('ratable_type')) {
                                'competitor_product' => CompetitorProduct::query()
                                    ->when($get('_brand_filter'), fn ($q, $brandId) => $q->where('brand_id', $brandId))
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray(),
                                'supplier_product' => SupplierProduct::query()->orderBy('name')->pluck('name', 'id')->toArray(),
                                'final_product' => FinalProduct::query()->orderBy('name')->pluck('name', 'id')->toArray(),
                                default => [],
                            };
                        })
                        ->searchable()
                        ->preload()
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
                    ->label('Allgemeine Bewertung')
                    ->rows(3)
                    ->columnSpanFull()
                    ->helperText('Freier Text zur Gesamteinschätzung.'),
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
