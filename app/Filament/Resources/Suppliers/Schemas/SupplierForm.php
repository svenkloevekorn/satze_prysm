<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Stammdaten')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Firmenname')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('country')
                            ->label('Land (ISO-Code, z.B. CN, IT, TR)')
                            ->maxLength(2),
                        Toggle::make('is_active')
                            ->label('Aktiv')
                            ->default(true),
                        Textarea::make('address')
                            ->label('Adresse')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Bewertung & Notizen')
                    ->schema([
                        TextInput::make('rating')
                            ->label('Bewertung (1-10)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->suffix('/10')
                            ->helperText('Deine eigene Einschätzung des Lieferanten.'),
                        Textarea::make('notes')
                            ->label('Notizen')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Nachhaltigkeit')
                    ->description('Bewertung und Zertifikate für Nachhaltigkeits-Performance des Lieferanten.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sustainability_score')
                            ->label('Nachhaltigkeits-Score (1-10)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->suffix('/10')
                            ->helperText('Wie gut schneidet der Lieferant insgesamt in Sachen Nachhaltigkeit ab?'),
                        Select::make('certifications')
                            ->label('Zertifikate')
                            ->multiple()
                            ->options([
                                'fair_trade' => 'Fair Trade',
                                'bluesign' => 'Bluesign',
                                'gots' => 'GOTS',
                                'oeko_tex' => 'OEKO-TEX',
                                'bsci' => 'BSCI',
                                'grs' => 'GRS (Global Recycled Standard)',
                                'iso_14001' => 'ISO 14001 (Umwelt)',
                                'sa_8000' => 'SA 8000 (Sozial)',
                                'pfc_free' => 'PFC-frei',
                                'climate_neutral' => 'Klimaneutral',
                            ])
                            ->searchable(),
                    ]),
            ]);
    }
}
