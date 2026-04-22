<?php

namespace App\Filament\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activitiesAsSubject';

    protected static ?string $title = 'Änderungshistorie';

    protected static ?string $modelLabel = 'Änderung';

    protected static ?string $pluralModelLabel = 'Änderungen';

    public function form(Schema $schema): Schema
    {
        return $schema;
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Wann')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('event')
                    ->label('Aktion')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('causer.name')
                    ->label('Wer')
                    ->placeholder('System'),
                TextColumn::make('properties')
                    ->label('Änderungen')
                    ->formatStateUsing(function ($state): string {
                        if (blank($state)) {
                            return '–';
                        }
                        $data = is_array($state) ? $state : (array) $state;
                        $attrs = $data['attributes'] ?? [];
                        $old = $data['old'] ?? [];
                        if (empty($attrs)) {
                            return '–';
                        }
                        $rows = [];
                        foreach ($attrs as $field => $newVal) {
                            $oldVal = $old[$field] ?? null;
                            $newStr = is_scalar($newVal) ? (string) $newVal : json_encode($newVal);
                            $oldStr = is_scalar($oldVal) ? (string) $oldVal : json_encode($oldVal);
                            $rows[] = "<strong>{$field}</strong>: „{$oldStr}".'" → „'."{$newStr}".'"';
                        }

                        return implode('<br>', array_slice($rows, 0, 5));
                    })
                    ->html()
                    ->wrap(),
            ]);
    }
}
