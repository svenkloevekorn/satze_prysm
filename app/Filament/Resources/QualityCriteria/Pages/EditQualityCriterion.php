<?php

namespace App\Filament\Resources\QualityCriteria\Pages;

use App\Filament\Resources\QualityCriteria\QualityCriterionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQualityCriterion extends EditRecord
{
    protected static string $resource = QualityCriterionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
