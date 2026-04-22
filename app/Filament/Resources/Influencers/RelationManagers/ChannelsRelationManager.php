<?php

namespace App\Filament\Resources\Influencers\RelationManagers;

use App\Enums\SocialPlatform;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChannelsRelationManager extends RelationManager
{
    protected static string $relationship = 'channels';

    protected static ?string $title = 'Social-Media-Kanäle';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('platform')
                ->label('Plattform')
                ->options(SocialPlatform::options())
                ->required(),
            TextInput::make('handle')
                ->label('Handle')
                ->placeholder('@username')
                ->maxLength(255),
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
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('handle')
            ->columns([
                TextColumn::make('platform')
                    ->label('Plattform')
                    ->badge()
                    ->formatStateUsing(fn (SocialPlatform $state) => $state->label())
                    ->color(fn (SocialPlatform $state) => $state->color())
                    ->icon(fn (SocialPlatform $state) => $state->icon()),
                TextColumn::make('handle')
                    ->label('Handle')
                    ->weight('bold'),
                TextColumn::make('followers')
                    ->label('Follower')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('engagement_rate')
                    ->label('Engagement')
                    ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 2, ',', '.').' %' : '–')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()->label('Kanal hinzufügen'),
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
