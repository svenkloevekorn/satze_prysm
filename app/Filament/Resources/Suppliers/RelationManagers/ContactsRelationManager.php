<?php

namespace App\Filament\Resources\Suppliers\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    protected static ?string $title = 'Ansprechpartner';

    protected static ?string $modelLabel = 'Ansprechpartner';

    protected static ?string $pluralModelLabel = 'Ansprechpartner';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('role')
                    ->label('Rolle / Funktion')
                    ->maxLength(255)
                    ->placeholder('z.B. Sales Manager'),
                TextInput::make('email')
                    ->label('E-Mail')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telefon')
                    ->tel()
                    ->maxLength(255),
                Textarea::make('notes')
                    ->label('Notizen')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('role')
                    ->label('Rolle')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('email')
                    ->label('E-Mail')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->copyable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Ansprechpartner hinzufügen'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
