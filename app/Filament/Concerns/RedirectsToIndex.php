<?php

namespace App\Filament\Concerns;

// Nach dem Speichern eines Create- oder Edit-Formulars zurück zur Listenansicht.
// In jeder Filament-Page per "use RedirectsToIndex;" einbinden.
trait RedirectsToIndex
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
