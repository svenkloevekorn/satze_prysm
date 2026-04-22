<?php

namespace App\Filament\Resources\Influencers\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InfluencerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Profil')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('country')
                        ->label('Land (ISO-Code)')
                        ->maxLength(2),
                    Toggle::make('is_active')
                        ->label('Aktiv')
                        ->default(true),
                    Textarea::make('bio')
                        ->label('Bio / Beschreibung')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
            Section::make('Kontakt')
                ->columns(2)
                ->schema([
                    TextInput::make('email')
                        ->label('E-Mail')
                        ->email(),
                    TextInput::make('contact_phone')
                        ->label('Telefon')
                        ->tel(),
                ]),
            Section::make('Profilbild')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->label('Profilbild')
                        ->collection('avatar')
                        ->image()
                        ->imageEditor()
                        ->avatar()
                        ->columnSpanFull(),
                ]),
            Section::make('Notizen')
                ->schema([
                    Textarea::make('notes')
                        ->label('Notizen')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
