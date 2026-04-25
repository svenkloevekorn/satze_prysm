<?php

use App\Enums\DevelopmentStatus;
use App\Enums\QualityCheckStatus;
use App\Enums\RatingSource;
use App\Enums\SocialPlatform;

it('liefert für jeden DevelopmentStatus Label, Color und Icon', function () {
    foreach (DevelopmentStatus::cases() as $status) {
        expect($status->label())->toBeString()->not->toBeEmpty();
        expect($status->color())->toBeString()->not->toBeEmpty();
        expect($status->icon())->toBeString()->not->toBeEmpty();
    }
});

it('liefert DevelopmentStatus::options als Wert→Label-Map', function () {
    $options = DevelopmentStatus::options();

    expect($options)->toBeArray()->not->toBeEmpty();
    foreach (DevelopmentStatus::cases() as $case) {
        expect($options)->toHaveKey($case->value, $case->label());
    }
});

it('liefert für jeden QualityCheckStatus Label, Color und Icon', function () {
    foreach (QualityCheckStatus::cases() as $status) {
        expect($status->label())->toBeString()->not->toBeEmpty();
        expect($status->color())->toBeString()->not->toBeEmpty();
        expect($status->icon())->toBeString()->not->toBeEmpty();
    }
});

it('liefert für jede SocialPlatform Label und Icon', function () {
    foreach (SocialPlatform::cases() as $platform) {
        expect($platform->label())->toBeString()->not->toBeEmpty();
        expect($platform->icon())->toBeString()->not->toBeEmpty();
    }
});

it('liefert für jede RatingSource Label und Icon', function () {
    foreach (RatingSource::cases() as $source) {
        expect($source->label())->toBeString()->not->toBeEmpty();
        expect($source->icon())->toBeString()->not->toBeEmpty();
    }
});

it('hat genau 8 DevelopmentStatus-Cases (MVP-Workflow)', function () {
    expect(DevelopmentStatus::cases())->toHaveCount(8);
});

it('hat genau 6 SocialPlatforms', function () {
    expect(SocialPlatform::cases())->toHaveCount(6);
});

it('hat den finalen DevelopmentStatus auf den Wert "final" gesetzt', function () {
    expect(DevelopmentStatus::Final->value)->toBe('final');
});
