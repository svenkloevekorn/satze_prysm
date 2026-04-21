<?php

namespace App\Providers;

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

        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'competitor_product' => \App\Models\CompetitorProduct::class,
            'supplier_product' => \App\Models\SupplierProduct::class,
        ]);
    }
}
