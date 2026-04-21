<?php

namespace App\Filament\Resources\CompetitorProducts\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\CompetitorProducts\CompetitorProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompetitorProduct extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = CompetitorProductResource::class;
}
