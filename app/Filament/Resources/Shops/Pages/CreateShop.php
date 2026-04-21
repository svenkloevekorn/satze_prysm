<?php

namespace App\Filament\Resources\Shops\Pages;

use App\Filament\Concerns\RedirectsToIndex;
use App\Filament\Resources\Shops\ShopResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShop extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = ShopResource::class;
}
