<?php

namespace App\Filament\Resources\Suppliers\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\Suppliers\SupplierResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplier extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = SupplierResource::class;
}
