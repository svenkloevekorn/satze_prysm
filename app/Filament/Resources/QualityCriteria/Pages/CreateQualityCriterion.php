<?php

namespace App\Filament\Resources\QualityCriteria\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\QualityCriteria\QualityCriterionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQualityCriterion extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = QualityCriterionResource::class;
}
