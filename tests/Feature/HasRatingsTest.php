<?php

use App\Models\CompetitorProduct;
use App\Models\Rating;
use App\Models\RatingDimension;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('liefert null wenn keine Ratings vorhanden sind', function () {
    $product = CompetitorProduct::factory()->create();

    expect($product->averageScore())->toBeNull();
});

it('berechnet den Durchschnitt aus mehreren Ratings', function () {
    $product = CompetitorProduct::factory()->create();
    $dimension = RatingDimension::factory()->create();

    foreach ([6, 8, 10] as $score) {
        Rating::factory()->create([
            'ratable_type' => 'competitor_product',
            'ratable_id' => $product->id,
            'rating_dimension_id' => $dimension->id,
            'user_id' => $this->user->id,
            'score' => $score,
        ]);
    }

    expect($product->averageScore())->toBe(8.0);
});

it('rundet den Durchschnitt auf 1 Dezimalstelle', function () {
    $product = CompetitorProduct::factory()->create();
    $dimension = RatingDimension::factory()->create();

    foreach ([7, 8] as $score) {
        Rating::factory()->create([
            'ratable_type' => 'competitor_product',
            'ratable_id' => $product->id,
            'rating_dimension_id' => $dimension->id,
            'user_id' => $this->user->id,
            'score' => $score,
        ]);
    }

    expect($product->averageScore())->toBe(7.5);
});

it('mischt Scores aus verschiedenen Dimensionen im Durchschnitt', function () {
    $product = CompetitorProduct::factory()->create();
    $design = RatingDimension::factory()->create(['name' => 'Design']);
    $material = RatingDimension::factory()->create(['name' => 'Material']);

    Rating::factory()->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
        'rating_dimension_id' => $design->id,
        'user_id' => $this->user->id,
        'score' => 4,
    ]);
    Rating::factory()->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
        'rating_dimension_id' => $material->id,
        'user_id' => $this->user->id,
        'score' => 10,
    ]);

    expect($product->averageScore())->toBe(7.0);
});

it('verändert den Durchschnitt nach dem Löschen einer Bewertung', function () {
    $product = CompetitorProduct::factory()->create();
    $dimension = RatingDimension::factory()->create();

    $low = Rating::factory()->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
        'rating_dimension_id' => $dimension->id,
        'user_id' => $this->user->id,
        'score' => 2,
    ]);
    Rating::factory()->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
        'rating_dimension_id' => $dimension->id,
        'user_id' => $this->user->id,
        'score' => 8,
    ]);

    expect($product->averageScore())->toBe(5.0);

    $low->delete();

    expect($product->fresh()->averageScore())->toBe(8.0);
});
