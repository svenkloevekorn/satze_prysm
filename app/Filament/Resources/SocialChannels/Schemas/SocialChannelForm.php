<?php

namespace App\Filament\Resources\SocialChannels\Schemas;

use App\Enums\SocialPlatform;
use App\Models\Brand;
use App\Models\Influencer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SocialChannelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Besitzer')
                ->columns(2)
                ->schema([
                    Select::make('owner_type')
                        ->label('Besitzer-Typ')
                        ->options([
                            'influencer' => 'Influencer',
                            'brand' => 'Marke (eigene oder Wettbewerb)',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($set) => $set('owner_id', null)),
                    Select::make('owner_id')
                        ->label('Besitzer')
                        ->options(fn ($get) => match ($get('owner_type')) {
                            'influencer' => Influencer::orderBy('name')->pluck('name', 'id'),
                            'brand' => Brand::orderBy('name')->pluck('name', 'id'),
                            default => [],
                        })
                        ->searchable()
                        ->required(),
                ]),
            Section::make('Kanal')
                ->columns(2)
                ->schema([
                    Select::make('platform')
                        ->label('Plattform')
                        ->options(SocialPlatform::options())
                        ->required(),
                    TextInput::make('handle')
                        ->label('Handle')
                        ->placeholder('@username'),
                    TextInput::make('url')
                        ->label('URL')
                        ->url()
                        ->columnSpanFull(),
                    TextInput::make('followers')
                        ->label('Follower')
                        ->numeric(),
                    TextInput::make('engagement_rate')
                        ->label('Engagement-Rate (%)')
                        ->numeric()
                        ->step(0.01)
                        ->suffix('%'),
                    TextInput::make('language')
                        ->label('Sprache')
                        ->maxLength(5),
                    TextInput::make('country')
                        ->label('Land (ISO-Code)')
                        ->maxLength(2),
                    TagsInput::make('categories')
                        ->label('Themen / Tags')
                        ->columnSpanFull(),
                    Toggle::make('is_active')
                        ->label('Aktiv')
                        ->default(true),
                    Textarea::make('notes')
                        ->label('Notizen')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
