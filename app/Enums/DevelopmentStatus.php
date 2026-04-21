<?php

namespace App\Enums;

enum DevelopmentStatus: string
{
    case Idea = 'idea';
    case InProgress = 'in_progress';
    case ConceptConfirmed = 'concept_confirmed';
    case TechSheetCreated = 'tech_sheet_created';
    case SentToSupplier = 'sent_to_supplier';
    case SampleReceived = 'sample_received';
    case Revised = 'revised';
    case Final = 'final';

    public function label(): string
    {
        return match ($this) {
            self::Idea => '1. Idee',
            self::InProgress => '2. In Ausarbeitung',
            self::ConceptConfirmed => '3. Konzept bestätigt',
            self::TechSheetCreated => '4. Tech Sheet erstellt',
            self::SentToSupplier => '5. An Lieferant gesendet',
            self::SampleReceived => '6. Sample erhalten',
            self::Revised => '7. Überarbeitet',
            self::Final => '8. Final',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Idea => 'gray',
            self::InProgress => 'info',
            self::ConceptConfirmed => 'info',
            self::TechSheetCreated => 'warning',
            self::SentToSupplier => 'warning',
            self::SampleReceived => 'warning',
            self::Revised => 'warning',
            self::Final => 'success',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Idea => 'heroicon-o-light-bulb',
            self::InProgress => 'heroicon-o-pencil-square',
            self::ConceptConfirmed => 'heroicon-o-check-circle',
            self::TechSheetCreated => 'heroicon-o-document-text',
            self::SentToSupplier => 'heroicon-o-paper-airplane',
            self::SampleReceived => 'heroicon-o-gift',
            self::Revised => 'heroicon-o-arrow-path',
            self::Final => 'heroicon-o-trophy',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}
