<?php

use App\Filament\Exports\CompetitorProductExporter;
use Filament\Actions\Exports\Models\Export;

it('hat alle Pflicht-Spalten', function () {
    $names = collect(CompetitorProductExporter::getColumns())
        ->map(fn ($c) => $c->getName())
        ->all();

    expect($names)->toContain(
        'name',
        'brand.name',
        'category.name',
        'description',
        'materials',
        'colors',
        'sizes',
        'price_min',
        'price_max',
        'ratings_avg_score',
    );
});

it('liefert deutsche Labels für alle Spalten', function () {
    $labels = collect(CompetitorProductExporter::getColumns())
        ->map(fn ($c) => $c->getLabel())
        ->all();

    expect($labels)->toContain('Produktname', 'Marke', 'Kategorie', 'Mindestpreis', 'Höchstpreis');
});

it('formatiert die Notification-Body bei erfolgreichem Export', function () {
    $export = new Export;
    $export->successful_rows = 25;
    $export->total_rows = 25;

    $body = CompetitorProductExporter::getCompletedNotificationBody($export);

    expect($body)
        ->toContain('25')
        ->toContain('exportiert');
});

it('zeigt fehlgeschlagene Zeilen in der Notification an', function () {
    $export = new Export;
    $export->successful_rows = 18;
    $export->total_rows = 20;

    $body = CompetitorProductExporter::getCompletedNotificationBody($export);

    expect($body)
        ->toContain('18')
        ->toContain('2')
        ->toContain('fehlgeschlagen');
});
