<?php

use App\Support\CsvSafe;

it('prefixt Werte die mit Gleichheitszeichen beginnen (Excel-Formel)', function () {
    expect(CsvSafe::value('=cmd|/c calc'))->toBe("'=cmd|/c calc");
});

it('prefixt Werte die mit Plus beginnen', function () {
    expect(CsvSafe::value('+1234'))->toBe("'+1234");
});

it('prefixt Werte die mit Minus beginnen', function () {
    expect(CsvSafe::value('-50'))->toBe("'-50");
});

it('prefixt Werte die mit At-Zeichen beginnen', function () {
    expect(CsvSafe::value('@SUM(A1:A10)'))->toBe("'@SUM(A1:A10)");
});

it('prefixt Werte die mit Tab beginnen', function () {
    expect(CsvSafe::value("\tEvil"))->toBe("'\tEvil");
});

it('prefixt Werte die mit Carriage-Return beginnen', function () {
    expect(CsvSafe::value("\rEvil"))->toBe("'\rEvil");
});

it('lässt harmlose Strings unverändert', function () {
    expect(CsvSafe::value('Castelli Climber Jersey'))->toBe('Castelli Climber Jersey');
    expect(CsvSafe::value('99.99'))->toBe('99.99');
    expect(CsvSafe::value('Polyester, Elasthan'))->toBe('Polyester, Elasthan');
});

it('lässt null und nicht-strings unverändert', function () {
    expect(CsvSafe::value(null))->toBeNull();
    expect(CsvSafe::value(42))->toBe(42);
    expect(CsvSafe::value(3.14))->toBe(3.14);
    expect(CsvSafe::value(true))->toBeTrue();
});

it('lässt leere Strings unverändert', function () {
    expect(CsvSafe::value(''))->toBe('');
});
