<?php

namespace App\Filament\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
                TextColumn::make('attribute_changes')
                    ->label('Änderungen')
                    ->state(fn ($record) => $record->attribute_changes)
                    ->formatStateUsing(function ($state): HtmlString {
                        if (blank($state)) {
                            return new HtmlString('–');
                        }
                        $data = is_array($state) ? $state : (array) $state;
                        $attrs = $data['attributes'] ?? [];
                        $old = $data['old'] ?? [];
                        if (empty($attrs)) {
                            return new HtmlString('–');
                        }
                        $rows = [];
                        foreach ($attrs as $field => $newVal) {
                            $oldVal = $old[$field] ?? null;
                            $newStr = is_scalar($newVal) ? (string) $newVal : json_encode($newVal);
                            $oldStr = is_scalar($oldVal) ? (string) $oldVal : json_encode($oldVal);
                            $rows[] = '<strong>'.e($field).'</strong>: „'.e($oldStr).'" → „'.e($newStr).'"';
                        }

                        return new HtmlString(implode('<br>', array_slice($rows, 0, 5)));
                    })
                    ->html()
                    ->wrap(),
            ]);
    }
}
