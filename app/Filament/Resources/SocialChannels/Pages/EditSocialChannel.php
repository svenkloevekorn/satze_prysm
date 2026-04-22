<?php

namespace App\Filament\Resources\SocialChannels\Pages;

use App\Filament\Resources\SocialChannels\SocialChannelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSocialChannel extends EditRecord
{
    protected static string $resource = SocialChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
