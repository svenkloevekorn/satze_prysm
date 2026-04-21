<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->label('Oberkategorie')
                    ->placeholder('— keine (ist selbst Oberkategorie) —')
                    ->options(function ($record) {
                        $query = Category::query()
                            ->whereNull('parent_id') // nur Top-Level
                            ->orderBy('name');

                        // Beim Bearbeiten: sich selbst ausschließen
                        if ($record) {
                            $query->where('id', '!=', $record->id);
                        }

                        return $query->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->helperText('Leer lassen = das ist eine Oberkategorie. Maximal 2 Ebenen erlaubt.')
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')
                    ->label('Slug (URL-Kürzel)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Wird automatisch aus dem Namen erzeugt.'),
                Textarea::make('description')
                    ->label('Beschreibung')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Sortier-Reihenfolge')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktiv')
                    ->default(true),
            ]);
    }
}
