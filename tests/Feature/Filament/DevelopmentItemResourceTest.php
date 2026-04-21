<?php

use App\Enums\DevelopmentStatus;
use App\Filament\Resources\DevelopmentItems\Pages\CreateDevelopmentItem;
use App\Filament\Resources\DevelopmentItems\Pages\EditDevelopmentItem;
use App\Filament\Resources\DevelopmentItems\Pages\ListDevelopmentItems;
use App\Filament\Resources\FinalProducts\Pages\ListFinalProducts;
use App\Models\Category;
use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\FinalProduct;
use App\Models\SupplierProduct;
use App\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('zeigt die Entwicklungs-Liste an', function () {
    DevelopmentItem::factory()->count(3)->create();
    livewire(ListDevelopmentItems::class)->assertSuccessful();
});

it('legt ein neues Entwicklungs-Item mit Status Idee an', function () {
    $category = Category::factory()->create();

    livewire(CreateDevelopmentItem::class)
        ->fillForm([
            'name' => 'Testidee',
            'category_id' => $category->id,
            'status' => DevelopmentStatus::Idea->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(DevelopmentItem::where('name', 'Testidee')->exists())->toBeTrue();
});

it('verlangt Name und Kategorie', function () {
    livewire(CreateDevelopmentItem::class)
        ->fillForm(['name' => '', 'category_id' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'category_id' => 'required']);
});

it('erstellt automatisch ein FinalProduct wenn Status auf final wechselt', function () {
    $item = DevelopmentItem::factory()->create([
        'status' => DevelopmentStatus::InProgress,
        'name' => 'Wird Final',
        'target_price' => 99.00,
    ]);

    expect(FinalProduct::where('development_item_id', $item->id)->exists())->toBeFalse();

    $item->update(['status' => DevelopmentStatus::Final]);

    $final = FinalProduct::where('development_item_id', $item->id)->first();
    expect($final)->not->toBeNull();
    expect($final->name)->toBe('Wird Final');
    expect((float) $final->retail_price)->toBe(99.00);
});

it('erstellt kein doppeltes FinalProduct bei mehrfachem Speichern', function () {
    $item = DevelopmentItem::factory()->create(['status' => DevelopmentStatus::Final]);

    $item->update(['notes' => 'Update 1']);
    $item->update(['notes' => 'Update 2']);

    expect(FinalProduct::where('development_item_id', $item->id)->count())->toBe(1);
});

it('verknuepft Wettbewerbsprodukte als Inspiration', function () {
    $item = DevelopmentItem::factory()->create();
    $competitor = CompetitorProduct::factory()->create();

    $item->competitorInspirations()->attach($competitor);

    expect($item->competitorInspirations)->toHaveCount(1);
    expect($item->competitorInspirations->first()->id)->toBe($competitor->id);
});

it('verknuepft Lieferanten-Produkte als Basis', function () {
    $item = DevelopmentItem::factory()->create();
    $supplier = SupplierProduct::factory()->create();

    $item->supplierBasis()->attach($supplier);

    expect($item->supplierBasis)->toHaveCount(1);
});

it('zeigt Finale-Produkte-Liste an', function () {
    FinalProduct::factory()->count(3)->create();
    livewire(ListFinalProducts::class)->assertSuccessful();
});

it('kann Bewertungen an ein FinalProduct haengen', function () {
    $product = FinalProduct::factory()->create();

    \App\Models\Rating::factory()->create([
        'ratable_type' => 'final_product',
        'ratable_id' => $product->id,
        'score' => 9,
    ]);

    expect($product->ratings)->toHaveCount(1);
    expect($product->averageScore())->toBe(9.0);
});

it('kann Entwicklungs-Item bearbeiten', function () {
    $item = DevelopmentItem::factory()->create(['name' => 'Alt']);

    livewire(EditDevelopmentItem::class, ['record' => $item->getRouteKey()])
        ->fillForm(['name' => 'Neu'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($item->fresh()->name)->toBe('Neu');
});

it('laedt Edit-Seite auch wenn Wettbewerbs-/Lieferantenprodukte existieren (JSON-Fix)', function () {
    // Regression: Postgres konnte SELECT DISTINCT auf Tabellen mit JSON-Spalten
    // nicht ausfuehren – das brach die Edit-Seite. Fix: Select nur id+name.
    \App\Models\CompetitorProduct::factory()->count(3)->create();
    \App\Models\SupplierProduct::factory()->count(3)->create();
    $item = DevelopmentItem::factory()->create();

    livewire(EditDevelopmentItem::class, ['record' => $item->getRouteKey()])
        ->assertSuccessful();
});
