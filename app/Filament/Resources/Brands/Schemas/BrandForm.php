<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Stammdaten')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('country')
                            ->label('Land (ISO-Code, z.B. IT, DE, US)')
                            ->maxLength(2)
                            ->placeholder('IT'),
                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://www.castelli-cycling.com')
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Beschreibung')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Social Media')
                    ->description('Links oder Handles der Marke auf sozialen Netzwerken.')
                    ->columns(2)
                    ->collapsed(fn ($record) => $record === null) // bei Neuanlage eingeklappt
                    ->schema([
                        TextInput::make('instagram')
                            ->label('Instagram')
                            ->prefix('@')
                            ->placeholder('castellicycling')
                            ->maxLength(255),
                        TextInput::make('facebook')
                            ->label('Facebook')
                            ->url()
                            ->placeholder('https://www.facebook.com/...')
                            ->maxLength(255),
                        TextInput::make('linkedin')
                            ->label('LinkedIn')
                            ->url()
                            ->placeholder('https://www.linkedin.com/company/...')
                            ->maxLength(255),
                        TextInput::make('tiktok')
                            ->label('TikTok')
                            ->prefix('@')
                            ->placeholder('castelli.cycling')
                            ->maxLength(255),
                        TextInput::make('youtube')
                            ->label('YouTube')
                            ->url()
                            ->placeholder('https://www.youtube.com/@...')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Notizen & Status')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notizen')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Interne Notizen: Stärken, Schwächen, Zielgruppe, Preissegment ...'),
                        Toggle::make('is_active')
                            ->label('Aktiv')
                            ->default(true),
                    ]),
            ]);
    }
}
