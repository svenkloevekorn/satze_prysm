<?php

namespace App\Filament\Resources\DevelopmentItems\Pages;

use App\Filament\Resources\DevelopmentItems\DevelopmentItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDevelopmentItem extends CreateRecord
{
    protected static string $resource = DevelopmentItemResource::class;
}
