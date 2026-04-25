<?php

use App\Enums\DevelopmentStatus;
use App\Models\Category;
use App\Models\DevelopmentItem;
use App\Models\FinalProduct;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('erstellt automatisch ein FinalProduct beim Wechsel auf Final', function () {
    $item = DevelopmentItem::factory()->create([
        'name' => 'Pro Summer Jersey v1',
        'status' => DevelopmentStatus::InProgress,
    ]);

    expect(FinalProduct::where('development_item_id', $item->id)->exists())->toBeFalse();

    $item->update(['status' => DevelopmentStatus::Final]);

    $final = FinalProduct::where('development_item_id', $item->id)->first();
    expect($final)->not->toBeNull();
    expect($final->name)->toBe('Pro Summer Jersey v1');
});

it('übernimmt Kategorie und Bilder vom DevelopmentItem ins FinalProduct', function () {
    $category = Category::factory()->create();
    $item = DevelopmentItem::factory()->create([
        'category_id' => $category->id,
        'status' => DevelopmentStatus::InProgress,
    ]);

    $item->update(['status' => DevelopmentStatus::Final]);

    $final = FinalProduct::where('development_item_id', $item->id)->first();
    expect($final->category_id)->toBe($category->id);
});

it('legt kein zweites FinalProduct an wenn Status mehrfach gesetzt wird', function () {
    $item = DevelopmentItem::factory()->create(['status' => DevelopmentStatus::Final]);

    expect(FinalProduct::where('development_item_id', $item->id)->count())->toBe(1);

    $item->touch();
    $item->update(['name' => 'Umbenannt']);

    expect(FinalProduct::where('development_item_id', $item->id)->count())->toBe(1);
});

it('legt KEIN FinalProduct an wenn Status NICHT auf Final ist', function () {
    $item = DevelopmentItem::factory()->create(['status' => DevelopmentStatus::Idea]);
    $item->update(['name' => 'Anderer Name']);
    $item->update(['status' => DevelopmentStatus::SampleReceived]);

    expect(FinalProduct::where('development_item_id', $item->id)->exists())->toBeFalse();
});

it('erkennt Final-Status korrekt via isFinal-Helper', function () {
    $item = DevelopmentItem::factory()->create(['status' => DevelopmentStatus::Final]);
    expect($item->isFinal())->toBeTrue();

    $item2 = DevelopmentItem::factory()->create(['status' => DevelopmentStatus::Idea]);
    expect($item2->isFinal())->toBeFalse();
});
