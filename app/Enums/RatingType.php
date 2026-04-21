<?php

namespace App\Enums;

enum RatingType: string
{
    case Internal = 'internal';
    case External = 'external';

    public function label(): string
    {
        return match ($this) {
            self::Internal => 'Intern',
            self::External => 'Extern',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Internal => 'primary',
            self::External => 'warning',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}
