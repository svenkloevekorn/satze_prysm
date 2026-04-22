<?php

namespace App\Filament\Resources\SocialChannels\Pages;

use App\Filament\Resources\SocialChannels\SocialChannelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSocialChannels extends ListRecords
{
    protected static string $resource = SocialChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
