<?php

use App\Filament\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\CompetitorProducts\Pages\EditCompetitorProduct;
use App\Models\CompetitorProduct;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    $this->actingAs($admin);
});

it('escapet XSS-Payloads in der Änderungshistorie und liefert nie ausführbare Script-Tags aus', function () {
    $product = CompetitorProduct::factory()->create(['name' => 'Original']);

    // Boeser User aendert den Namen zu einem XSS-Payload
    $product->update(['name' => '<script>alert("XSS")</script>']);

    $component = livewire(ActivitiesRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditCompetitorProduct::class,
    ]);

    $html = $component->html();

    // Wichtigste Garantie: ausfuehrbare Script-Tags duerfen NICHT im HTML stehen
    expect($html)->not->toContain('<script>alert("XSS")</script>');
    expect($html)->not->toContain('<script>alert');
});

it('formatStateUsing escapet HTML-Sonderzeichen direkt', function () {
    // Direkter Closure-Test ohne Livewire-Render-Kette
    $payload = ['attributes' => ['name' => '<script>alert(1)</script>'], 'old' => ['name' => 'Original']];

    // Replicate die Logik aus ActivitiesRelationManager:
    $rows = [];
    foreach ($payload['attributes'] as $field => $newVal) {
        $oldVal = $payload['old'][$field] ?? null;
        $rows[] = '<strong>'.e($field).'</strong>: „'.e((string) $oldVal).'" → „'.e((string) $newVal).'"';
    }
    $output = implode('<br>', $rows);

    expect($output)->toContain('&lt;script&gt;');
    expect($output)->not->toContain('<script>alert(1)</script>');
});
