<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\FinalProduct;
use App\Models\Influencer;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });

        Relation::morphMap([
            'competitor_product' => CompetitorProduct::class,
            'supplier_product' => SupplierProduct::class,
            'final_product' => FinalProduct::class,
            'development_item' => DevelopmentItem::class,
            'influencer' => Influencer::class,
            'brand' => Brand::class,
        ]);

        Event::listen(Login::class, function (Login $event): void {
            if ($event->user instanceof User) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
            }
        });
    }
}
