<?php

namespace App\Filament\Resources\Shops\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ShopForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('url')
                    ->label('Website')
                    ->url()
                    ->maxLength(255),
                TextInput::make('country')
                    ->label('Land (ISO-Code, z.B. DE, US)')
                    ->maxLength(2)
                    ->placeholder('DE'),
                Toggle::make('is_active')
                    ->label('Aktiv')
                    ->default(true),
                Textarea::make('notes')
                    ->label('Notizen')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
