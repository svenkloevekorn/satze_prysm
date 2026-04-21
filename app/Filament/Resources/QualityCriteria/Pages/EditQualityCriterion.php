<?php

namespace App\Filament\Resources\QualityCriteria\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\QualityCriteria\QualityCriterionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQualityCriterion extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = QualityCriterionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
