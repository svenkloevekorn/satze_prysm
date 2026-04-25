<?php

use App\Models\Category;
use App\Models\QualityCriterion;
use Illuminate\Validation\ValidationException;

it('generiert automatisch einen Slug aus dem Namen', function () {
    $cat = Category::create(['name' => 'Cycling Jerseys']);

    expect($cat->slug)->toBe('cycling-jerseys');
});

it('respektiert einen vorgegebenen Slug', function () {
    $cat = Category::create(['name' => 'Cycling Jerseys', 'slug' => 'custom-slug']);

    expect($cat->slug)->toBe('custom-slug');
});

it('liefert isTopLevel=true für Top-Level-Kategorien', function () {
    $top = Category::create(['name' => 'Cycling']);

    expect($top->isTopLevel())->toBeTrue();
});

it('liefert isTopLevel=false für Unterkategorien', function () {
    $top = Category::create(['name' => 'Cycling']);
    $child = Category::create(['name' => 'Jerseys', 'parent_id' => $top->id]);

    expect($child->isTopLevel())->toBeFalse();
});

it('formatiert fullName mit Parent-Pfeil', function () {
    $top = Category::create(['name' => 'Cycling']);
    $child = Category::create(['name' => 'Jerseys', 'parent_id' => $top->id]);

    expect($child->fullName())->toBe('Cycling › Jerseys');
    expect($top->fullName())->toBe('Cycling');
});

it('verhindert dass eine Kategorie ihr eigener Parent ist', function () {
    $cat = Category::create(['name' => 'Self']);

    expect(fn () => $cat->update(['parent_id' => $cat->id]))
        ->toThrow(ValidationException::class);
});

it('verhindert mehr als 2 Ebenen', function () {
    $top = Category::create(['name' => 'Cycling']);
    $child = Category::create(['name' => 'Jerseys', 'parent_id' => $top->id]);

    expect(fn () => Category::create(['name' => 'Sub-Sub', 'parent_id' => $child->id]))
        ->toThrow(ValidationException::class);
});

it('verhindert dass eine Top-Kategorie mit Kindern selbst Kind wird', function () {
    $top1 = Category::create(['name' => 'Top1']);
    $top2 = Category::create(['name' => 'Top2']);
    Category::create(['name' => 'Child', 'parent_id' => $top1->id]);

    expect(fn () => $top1->update(['parent_id' => $top2->id]))
        ->toThrow(ValidationException::class);
});

it('liefert children sortiert nach sort_order', function () {
    $top = Category::create(['name' => 'Cycling']);
    Category::create(['name' => 'C', 'parent_id' => $top->id, 'sort_order' => 30]);
    Category::create(['name' => 'A', 'parent_id' => $top->id, 'sort_order' => 10]);
    Category::create(['name' => 'B', 'parent_id' => $top->id, 'sort_order' => 20]);

    expect($top->children->pluck('name')->all())->toBe(['A', 'B', 'C']);
});

it('verknüpft Quality-Kriterien per n:m', function () {
    $cat = Category::create(['name' => 'Cycling']);
    $criterion = QualityCriterion::factory()->create();

    $cat->qualityCriteria()->attach($criterion->id);

    expect($cat->qualityCriteria)->toHaveCount(1);
    expect($cat->qualityCriteria->first()->id)->toBe($criterion->id);
});
