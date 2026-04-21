<?php

use App\Enums\DevelopmentStatus;
use App\Filament\Widgets\LetzteAenderungenWidget;
use App\Filament\Widgets\LetzteBewertungenWidget;
use App\Filament\Widgets\OffeneEntwicklungenWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\UnbewerteteProdukteWidget;
use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\Rating;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('rendert das Stats-Widget', function () {
    CompetitorProduct::factory()->count(2)->create();
    DevelopmentItem::factory()->count(2)->create(['status' => DevelopmentStatus::InProgress]);

    livewire(StatsOverview::class)->assertSuccessful();
});

it('rendert das Widget Offene Entwicklungen', function () {
    DevelopmentItem::factory()->count(3)->create(['status' => DevelopmentStatus::InProgress]);

    livewire(OffeneEntwicklungenWidget::class)->assertSuccessful();
});

it('schliesst finale Items aus dem Widget "Offene Entwicklungen" aus', function () {
    $open = DevelopmentItem::factory()->create(['status' => DevelopmentStatus::Idea]);
    $final = DevelopmentItem::factory()->create(['status' => DevelopmentStatus::Final]);

    livewire(OffeneEntwicklungenWidget::class)
        ->assertCanSeeTableRecords([$open])
        ->assertCanNotSeeTableRecords([$final]);
});

it('rendert das Widget Unbewertete Produkte', function () {
    CompetitorProduct::factory()->count(3)->create();

    livewire(UnbewerteteProdukteWidget::class)->assertSuccessful();
});

it('zeigt nur unbewertete Produkte im Widget', function () {
    $ohne = CompetitorProduct::factory()->create(['name' => 'Ohne Bewertung']);
    $mit = CompetitorProduct::factory()->create(['name' => 'Mit Bewertung']);
    Rating::factory()->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $mit->id,
    ]);

    livewire(UnbewerteteProdukteWidget::class)
        ->assertCanSeeTableRecords([$ohne])
        ->assertCanNotSeeTableRecords([$mit]);
});

it('rendert das Widget Letzte Änderungen', function () {
    CompetitorProduct::factory()->count(3)->create();

    livewire(LetzteAenderungenWidget::class)->assertSuccessful();
});

it('rendert das Widget Letzte Bewertungen', function () {
    $product = CompetitorProduct::factory()->create();
    Rating::factory()->count(3)->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
    ]);

    livewire(LetzteBewertungenWidget::class)->assertSuccessful();
});
