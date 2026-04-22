<?php

namespace App\Enums;

enum QualityCheckStatus: string
{
    case Pass = 'pass';
    case Fail = 'fail';
    case NotApplicable = 'not_applicable';
    case Pending = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::Pass => 'Bestanden',
            self::Fail => 'Nicht bestanden',
            self::NotApplicable => 'Nicht anwendbar',
            self::Pending => 'Offen',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pass => 'success',
            self::Fail => 'danger',
            self::NotApplicable => 'gray',
            self::Pending => 'warning',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Pass => 'heroicon-o-check-circle',
            self::Fail => 'heroicon-o-x-circle',
            self::NotApplicable => 'heroicon-o-minus-circle',
            self::Pending => 'heroicon-o-clock',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}
