<?php

namespace App\Filament\Resources\QualityCriteria\Pages;

use App\Filament\Resources\QualityCriteria\QualityCriterionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQualityCriteria extends ListRecords
{
    protected static string $resource = QualityCriterionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
