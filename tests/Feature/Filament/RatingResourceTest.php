<?php

use App\Enums\RatingSource;
use App\Filament\Resources\CompetitorProducts\Pages\EditCompetitorProduct;
use App\Filament\Resources\CompetitorProducts\RelationManagers\RatingsRelationManager as CompetitorRatingsRM;
use App\Filament\Resources\Ratings\Pages\CreateRating;
use App\Filament\Resources\Ratings\Pages\ListRatings;
use App\Filament\Resources\SupplierProducts\Pages\EditSupplierProduct;
use App\Filament\Resources\SupplierProducts\RelationManagers\RatingsRelationManager as SupplierRatingsRM;
use App\Models\Brand;
use App\Models\CompetitorProduct;
use App\Models\Rating;
use App\Models\RatingDimension;
use App\Models\SupplierProduct;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('zeigt die Bewertungs-Liste an', function () {
    Rating::factory()->count(3)->create();
    livewire(ListRatings::class)->assertSuccessful();
});

it('legt eine Bewertung über die eigenständige Resource an', function () {
    $product = CompetitorProduct::factory()->create();

    livewire(CreateRating::class)
        ->fillForm([
            'ratable_type' => 'competitor_product',
            'ratable_id' => $product->id,
            'sources' => [RatingSource::ProductWorn->value],
            'score' => 8,
            'comment' => 'Test Kommentar',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Rating::where('ratable_id', $product->id)->where('score', 8)->exists())->toBeTrue();
});

it('filtert Produkte nach gewählter Marke im Rating-Formular', function () {
    $brandA = Brand::factory()->create(['name' => 'Marke A']);
    $brandB = Brand::factory()->create(['name' => 'Marke B']);
    $prodA = CompetitorProduct::factory()->create(['name' => 'A-Produkt', 'brand_id' => $brandA->id]);
    $prodB = CompetitorProduct::factory()->create(['name' => 'B-Produkt', 'brand_id' => $brandB->id]);

    // Produkt-Dropdown ohne Filter: beide sichtbar
    $component = livewire(CreateRating::class)
        ->fillForm(['ratable_type' => 'competitor_product']);

    $optionsOhneFilter = $component->instance()->form->getComponent('ratable_id')->getOptions();
    expect($optionsOhneFilter)->toHaveKey($prodA->id)->toHaveKey($prodB->id);

    // Mit Filter auf Marke A: nur A-Produkt
    $component->fillForm(['_brand_filter' => $brandA->id]);
    $optionsGefiltert = $component->instance()->form->getComponent('ratable_id')->getOptions();
    expect($optionsGefiltert)->toHaveKey($prodA->id)->not->toHaveKey($prodB->id);
});

it('speichert den Marken-Filter NICHT in der Bewertung (nur UI-Filter)', function () {
    $brand = Brand::factory()->create();
    $product = CompetitorProduct::factory()->create(['brand_id' => $brand->id]);

    livewire(CreateRating::class)
        ->fillForm([
            'ratable_type' => 'competitor_product',
            '_brand_filter' => $brand->id,
            'ratable_id' => $product->id,
            'sources' => [RatingSource::ProductWorn->value],
            'score' => 7,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    // Rating-Tabelle hat keine brand_id-Spalte; wichtig ist nur, dass es keinen Fehler gibt
    $rating = Rating::where('ratable_id', $product->id)->first();
    expect($rating)->not->toBeNull();
    expect($rating->getAttributes())->not->toHaveKey('_brand_filter');
});

it('verlangt einen Score zwischen 1 und 10', function () {
    $product = CompetitorProduct::factory()->create();

    livewire(CreateRating::class)
        ->fillForm([
            'ratable_type' => 'competitor_product',
            'ratable_id' => $product->id,
            'score' => 11,
        ])
        ->call('create')
        ->assertHasFormErrors(['score']);
});

it('verknuepft Bewertungen polymorph mit Wettbewerbsprodukt', function () {
    $product = CompetitorProduct::factory()->create();
    Rating::factory()->count(3)->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
    ]);

    expect($product->ratings)->toHaveCount(3);
    expect($product->ratings->first()->ratable->is($product))->toBeTrue();
});

it('verknuepft Bewertungen polymorph mit Lieferanten-Produkt', function () {
    $product = SupplierProduct::factory()->create();
    Rating::factory()->count(2)->create([
        'ratable_type' => 'supplier_product',
        'ratable_id' => $product->id,
    ]);

    expect($product->ratings)->toHaveCount(2);
});

it('berechnet den Durchschnitts-Score', function () {
    $product = CompetitorProduct::factory()->create();
    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'score' => 6]);
    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'score' => 10]);

    expect($product->averageScore())->toBe(8.0);
});

it('zeigt Bewertungen im CompetitorProduct-RelationManager', function () {
    $product = CompetitorProduct::factory()->create();
    Rating::factory()->count(2)->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
    ]);

    livewire(CompetitorRatingsRM::class, [
        'ownerRecord' => $product,
        'pageClass' => EditCompetitorProduct::class,
    ])->assertSuccessful();
});

it('zeigt Bewertungen im SupplierProduct-RelationManager', function () {
    $product = SupplierProduct::factory()->create();
    Rating::factory()->count(2)->create([
        'ratable_type' => 'supplier_product',
        'ratable_id' => $product->id,
    ]);

    livewire(SupplierRatingsRM::class, [
        'ownerRecord' => $product,
        'pageClass' => EditSupplierProduct::class,
    ])->assertSuccessful();
});

it('speichert und filtert Bewertungen nach Quellen (Multi-Select)', function () {
    $product = CompetitorProduct::factory()->create();

    Rating::factory()->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
        'sources' => ['product_worn', 'product_ordered'],
    ]);
    Rating::factory()->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
        'sources' => ['forum_posts'],
    ]);

    // Filter: Bewertungen mit Quelle "product_worn"
    expect($product->ratings()->whereJsonContains('sources', 'product_worn')->count())->toBe(1);
    expect($product->ratings()->whereJsonContains('sources', 'forum_posts')->count())->toBe(1);
    expect($product->ratings)->toHaveCount(2);

    // Die erste Bewertung hat beide Quellen
    $first = $product->ratings()->whereJsonContains('sources', 'product_worn')->first();
    expect($first->sources)->toHaveCount(2);
});

it('erlaubt Bewertung mit oder ohne Dimension', function () {
    $product = CompetitorProduct::factory()->create();
    $dim = RatingDimension::factory()->create();

    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'rating_dimension_id' => null]);
    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'rating_dimension_id' => $dim->id]);

    expect($product->ratings)->toHaveCount(2);
    expect($product->ratings->whereNull('rating_dimension_id'))->toHaveCount(1);
});
