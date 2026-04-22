<?php

namespace App\Models\Concerns;

use App\Enums\QualityCheckStatus;
use App\Models\QualityCheck;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasQualityChecks
{
    public function qualityChecks(): MorphMany
    {
        return $this->morphMany(QualityCheck::class, 'checkable');
    }

    /**
     * Prozentualer Anteil bestandener Checks (pass / (pass+fail)).
     * Offene + n.a. werden nicht gewertet.
     */
    public function qualityScore(): ?int
    {
        $total = $this->qualityChecks()
            ->whereIn('status', [QualityCheckStatus::Pass->value, QualityCheckStatus::Fail->value])
            ->count();

        if ($total === 0) {
            return null;
        }

        $passed = $this->qualityChecks()
            ->where('status', QualityCheckStatus::Pass->value)
            ->count();

        return (int) round(($passed / $total) * 100);
    }
}
