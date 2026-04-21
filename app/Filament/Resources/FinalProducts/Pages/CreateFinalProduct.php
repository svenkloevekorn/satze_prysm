<?php

namespace App\Filament\Resources\FinalProducts\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\FinalProducts\FinalProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinalProduct extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = FinalProductResource::class;
}
