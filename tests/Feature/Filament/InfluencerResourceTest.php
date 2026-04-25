<?php

use App\Filament\Resources\Influencers\Pages\CreateInfluencer;
use App\Filament\Resources\Influencers\Pages\EditInfluencer;
use App\Filament\Resources\Influencers\Pages\ListInfluencers;
use App\Models\Influencer;
use App\Models\SocialChannel;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('zeigt die Influencer-Liste an', function () {
    Influencer::factory()->count(3)->create();
    livewire(ListInfluencers::class)->assertSuccessful();
});

it('legt einen neuen Influencer an', function () {
    livewire(CreateInfluencer::class)
        ->fillForm([
            'name' => 'Marie Schreiber',
            'country' => 'DE',
            'bio' => 'Cycling-Creator aus Berlin',
            'email' => 'marie@example.com',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $influencer = Influencer::where('email', 'marie@example.com')->first();
    expect($influencer)->not->toBeNull();
    expect($influencer->name)->toBe('Marie Schreiber');
    expect($influencer->is_active)->toBeTrue();
});

it('verlangt einen Namen', function () {
    livewire(CreateInfluencer::class)
        ->fillForm(['name' => '', 'email' => 'x@example.com'])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('öffnet die Edit-Seite für einen Influencer', function () {
    $influencer = Influencer::factory()->create();

    livewire(EditInfluencer::class, ['record' => $influencer->getRouteKey()])
        ->assertSuccessful();
});

it('verknüpft polymorphe Channels mit dem Influencer', function () {
    $influencer = Influencer::factory()->create();
    SocialChannel::factory()->count(2)->create([
        'owner_type' => 'influencer',
        'owner_id' => $influencer->id,
    ]);

    expect($influencer->channels)->toHaveCount(2);
});
