<?php

namespace App\Filament\Resources\DevelopmentItems\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\DevelopmentItems\DevelopmentItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDevelopmentItem extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = DevelopmentItemResource::class;
}
