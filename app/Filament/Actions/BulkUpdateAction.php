<?php

namespace App\Filament\Actions;

use Closure;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class BulkUpdateAction
{
    /**
     * Bulk-Action mit einem Update-Feld (Standard-Fall).
     *
     * @param  array<int, mixed>  $schema  Filament-Form-Komponenten
     */
    public static function singleField(
        string $name,
        string $label,
        string $icon,
        array $schema,
        string $field,
        string $successLabel = 'Datensätze aktualisiert',
    ): BulkAction {
        return self::make(
            name: $name,
            label: $label,
            icon: $icon,
            schema: $schema,
            update: fn (array $data) => [$field => $data[$field]],
            successLabel: $successLabel,
        );
    }

    /**
     * Bulk-Action mit beliebiger Update-Logik (z.B. Multi-Field, optional).
     *
     * Die `$update`-Closure bekommt die Form-Daten und gibt das Update-Array zurück.
     * Wenn sie ein leeres Array zurückgibt, wird abgebrochen mit Warnung.
     *
     * @param  array<int, mixed>  $schema
     * @param  Closure(array<string, mixed>): array<string, mixed>  $update
     */
    public static function make(
        string $name,
        string $label,
        string $icon,
        array $schema,
        Closure $update,
        string $successLabel = 'Datensätze aktualisiert',
        string $emptyWarning = 'Bitte mindestens ein Feld ausfüllen.',
    ): BulkAction {
        return BulkAction::make($name)
            ->label($label)
            ->icon($icon)
            ->schema($schema)
            ->action(function (array $data, Collection $records) use ($update, $successLabel, $emptyWarning) {
                $payload = $update($data);

                if (empty($payload)) {
                    Notification::make()
                        ->title($emptyWarning)
                        ->warning()
                        ->send();

                    return;
                }

                $records->each(fn ($record) => $record->update($payload));

                Notification::make()
                    ->title($records->count().' '.$successLabel)
                    ->success()
                    ->send();
            })
            ->deselectRecordsAfterCompletion();
    }
}
