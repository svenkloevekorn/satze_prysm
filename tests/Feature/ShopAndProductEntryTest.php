<?php

use App\Models\CompetitorProduct;
use App\Models\ProductShopEntry;
use App\Models\Shop;

it('verknüpft mehrere Shop-Einträge mit einem Wettbewerbsprodukt', function () {
    $product = CompetitorProduct::factory()->create();
    $shop1 = Shop::factory()->create(['name' => 'Bike-Discount']);
    $shop2 = Shop::factory()->create(['name' => 'Rose']);

    ProductShopEntry::factory()->create([
        'competitor_product_id' => $product->id,
        'shop_id' => $shop1->id,
        'observed_price' => 89.99,
    ]);
    ProductShopEntry::factory()->create([
        'competitor_product_id' => $product->id,
        'shop_id' => $shop2->id,
        'observed_price' => 99.99,
    ]);

    expect($product->shopEntries)->toHaveCount(2);
});

it('löscht ProductShopEntries beim Löschen des Wettbewerbsprodukts (cascade)', function () {
    $product = CompetitorProduct::factory()->create();
    ProductShopEntry::factory()->count(3)->create(['competitor_product_id' => $product->id]);

    expect(ProductShopEntry::count())->toBe(3);

    $product->delete();

    expect(ProductShopEntry::count())->toBe(0);
});

it('Shop hat ein url-Attribut', function () {
    $shop = Shop::factory()->create(['url' => 'https://www.bike-discount.de']);

    expect($shop->url)->toBe('https://www.bike-discount.de');
});
