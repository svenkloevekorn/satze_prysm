<?php

use App\Filament\Resources\SocialChannels\Pages\CreateSocialChannel;
use App\Filament\Resources\SocialChannels\Pages\EditSocialChannel;
use App\Filament\Resources\SocialChannels\Pages\ListSocialChannels;
use App\Models\Brand;
use App\Models\ChannelMetric;
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

it('zeigt die SocialChannel-Liste an', function () {
    SocialChannel::factory()->count(3)->create();
    livewire(ListSocialChannels::class)->assertSuccessful();
});

it('legt einen neuen Channel mit Influencer als Owner an', function () {
    $influencer = Influencer::factory()->create();

    livewire(CreateSocialChannel::class)
        ->fillForm([
            'owner_type' => 'influencer',
            'owner_id' => $influencer->id,
            'platform' => 'instagram',
            'handle' => 'marie.cycles',
            'url' => 'https://instagram.com/marie.cycles',
            'followers' => 87000,
            'engagement_rate' => 3.8,
            'country' => 'DE',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $channel = SocialChannel::where('handle', 'marie.cycles')->first();
    expect($channel)->not->toBeNull();
    expect($channel->owner_type)->toBe('influencer');
    expect($channel->owner_id)->toBe($influencer->id);
    expect($channel->owner)->toBeInstanceOf(Influencer::class);
    expect($channel->followers)->toBe(87000);
});

it('legt einen Channel mit Brand als polymorphem Owner an', function () {
    $brand = Brand::factory()->create();

    $channel = SocialChannel::factory()->create([
        'owner_type' => 'brand',
        'owner_id' => $brand->id,
        'platform' => 'instagram',
    ]);

    expect($channel->owner)->toBeInstanceOf(Brand::class);
    expect($channel->owner->id)->toBe($brand->id);
});

it('verlangt einen Owner', function () {
    livewire(CreateSocialChannel::class)
        ->fillForm([
            'owner_id' => null,
            'platform' => 'instagram',
            'handle' => 'foo',
        ])
        ->call('create')
        ->assertHasFormErrors(['owner_id']);
});

it('verlangt eine Platform', function () {
    $influencer = Influencer::factory()->create();

    livewire(CreateSocialChannel::class)
        ->fillForm([
            'owner_type' => 'influencer',
            'owner_id' => $influencer->id,
            'platform' => null,
            'handle' => 'foo',
        ])
        ->call('create')
        ->assertHasFormErrors(['platform']);
});

it('öffnet die Edit-Seite für einen Channel', function () {
    $channel = SocialChannel::factory()->create();

    livewire(EditSocialChannel::class, ['record' => $channel->getRouteKey()])
        ->assertSuccessful();
});

it('liefert die letzte ChannelMetric als latestMetric', function () {
    $channel = SocialChannel::factory()->create();

    ChannelMetric::factory()->create([
        'social_channel_id' => $channel->id,
        'captured_at' => now()->subDays(5),
        'followers' => 1000,
    ]);

    $latest = ChannelMetric::factory()->create([
        'social_channel_id' => $channel->id,
        'captured_at' => now(),
        'followers' => 1500,
    ]);

    expect($channel->latestMetric()->id)->toBe($latest->id);
    expect($channel->latestMetric()->followers)->toBe(1500);
});
