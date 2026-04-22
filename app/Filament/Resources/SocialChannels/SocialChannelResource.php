<?php

namespace App\Filament\Resources\SocialChannels;

use App\Filament\Resources\SocialChannels\Pages\CreateSocialChannel;
use App\Filament\Resources\SocialChannels\Pages\EditSocialChannel;
use App\Filament\Resources\SocialChannels\Pages\ListSocialChannels;
use App\Filament\Resources\SocialChannels\RelationManagers\MetricsRelationManager;
use App\Filament\Resources\SocialChannels\Schemas\SocialChannelForm;
use App\Filament\Resources\SocialChannels\Tables\SocialChannelsTable;
use App\Models\SocialChannel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SocialChannelResource extends Resource
{
    protected static ?string $model = SocialChannel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Alle Kanäle';

    protected static ?string $modelLabel = 'Social-Kanal';

    protected static ?string $pluralModelLabel = 'Social-Kanäle';

    protected static string|\UnitEnum|null $navigationGroup = 'Social Media';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SocialChannelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SocialChannelsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MetricsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSocialChannels::route('/'),
            'create' => CreateSocialChannel::route('/create'),
            'edit' => EditSocialChannel::route('/{record}/edit'),
        ];
    }
}
