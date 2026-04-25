<?php

use App\Models\Brand;
use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\FinalProduct;
use App\Models\Influencer;
use App\Models\SupplierProduct;
use Illuminate\Database\Eloquent\Relations\Relation;

it('hat alle erwarteten Morph-Aliase registriert', function () {
    $map = Relation::morphMap();

    expect($map)
        ->toHaveKey('competitor_product', CompetitorProduct::class)
        ->toHaveKey('supplier_product', SupplierProduct::class)
        ->toHaveKey('final_product', FinalProduct::class)
        ->toHaveKey('development_item', DevelopmentItem::class)
        ->toHaveKey('influencer', Influencer::class)
        ->toHaveKey('brand', Brand::class);
});

it('liefert für jedes Produkt-Model den korrekten Morph-Class-String', function () {
    expect((new CompetitorProduct)->getMorphClass())->toBe('competitor_product');
    expect((new SupplierProduct)->getMorphClass())->toBe('supplier_product');
    expect((new FinalProduct)->getMorphClass())->toBe('final_product');
    expect((new DevelopmentItem)->getMorphClass())->toBe('development_item');
    expect((new Influencer)->getMorphClass())->toBe('influencer');
    expect((new Brand)->getMorphClass())->toBe('brand');
});
