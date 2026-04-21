<?php

namespace App\Filament\Resources\SupplierProducts\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\SupplierProducts\SupplierProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplierProduct extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = SupplierProductResource::class;
}
