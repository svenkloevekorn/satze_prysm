<?php

namespace App\Providers;

use App\Models\CompetitorProduct;
use App\Models\FinalProduct;
use App\Models\SupplierProduct;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        ]);
    }
}
