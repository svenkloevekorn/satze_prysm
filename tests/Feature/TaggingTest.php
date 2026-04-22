<?php

use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\Influencer;
use Spatie\Tags\Tag;

it('speichert Tags an einem Wettbewerbsprodukt', function () {
    $product = CompetitorProduct::factory()->create();
    $product->attachTags(['Best-Seller', 'Sommer 2026']);

    expect($product->fresh()->tags)->toHaveCount(2);
    expect($product->fresh()->tags->pluck('name')->toArray())
        ->toContain('Best-Seller')
        ->toContain('Sommer 2026');
});

it('teilt Tags zwischen verschiedenen Modellen', function () {
    $product = CompetitorProduct::factory()->create();
    $item = DevelopmentItem::factory()->create();
    $influencer = Influencer::factory()->create();

    $product->attachTag('Trend');
    $item->attachTag('Trend');
    $influencer->attachTag('Trend');

    expect($product->fresh()->tags->pluck('name')->toArray())->toContain('Trend');
    expect($item->fresh()->tags->pluck('name')->toArray())->toContain('Trend');
    expect($influencer->fresh()->tags->pluck('name')->toArray())->toContain('Trend');

    expect(Tag::where('name->de', 'Trend')->count())->toBe(1);
});

it('kann Tags wieder entfernen', function () {
    $product = CompetitorProduct::factory()->create();
    $product->attachTags(['A', 'B', 'C']);
    $product->detachTag('B');

    expect($product->fresh()->tags->pluck('name')->toArray())
        ->toContain('A')
        ->toContain('C')
        ->not->toContain('B');
});
