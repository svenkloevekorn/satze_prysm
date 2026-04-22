<?php

namespace App\Filament\Resources\CompetitorProducts\RelationManagers;

use App\Enums\QualityCheckStatus;
use App\Models\QualityCheck;
use App\Models\QualityCriterion;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QualityChecksRelationManager extends RelationManager
{
    protected static string $relationship = 'qualityChecks';

    protected static ?string $title = 'Qualitäts-Checkliste';

    protected static ?string $modelLabel = 'Qualitäts-Check';

    protected static ?string $pluralModelLabel = 'Qualitäts-Checks';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('quality_criterion_id')
                ->label('Kriterium')
                ->options(fn () => QualityCriterion::where('is_active', true)->pluck('name', 'id'))
                ->required()
                ->searchable(),
            Select::make('status')
                ->label('Status')
                ->options(QualityCheckStatus::options())
                ->required()
                ->default(QualityCheckStatus::Pending->value),
            Textarea::make('comment')
                ->label('Kommentar')
                ->rows(3)
                ->columnSpanFull(),
            DatePicker::make('checked_at')
                ->label('Geprüft am')
                ->default(now())
                ->displayFormat('d.m.Y'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('criterion.name')
                    ->label('Kriterium')
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (QualityCheckStatus $state) => $state->label())
                    ->color(fn (QualityCheckStatus $state) => $state->color())
                    ->icon(fn (QualityCheckStatus $state) => $state->icon()),
                TextColumn::make('comment')
                    ->label('Kommentar')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->comment),
                TextColumn::make('user.name')
                    ->label('Geprüft von')
                    ->toggleable(),
                TextColumn::make('checked_at')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('initFromCategory')
                    ->label('Checkliste aus Kategorie füllen')
                    ->icon('heroicon-o-rectangle-stack')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Checkliste automatisch befüllen')
                    ->modalDescription('Erzeugt pro Qualitätskriterium der Kategorie einen offenen Check – bestehende bleiben unverändert.')
                    ->action(function () {
                        $owner = $this->getOwnerRecord();
                        $categoryId = $owner->category_id ?? null;

                        if (! $categoryId) {
                            Notification::make()->title('Keine Kategorie am Objekt gesetzt')->danger()->send();

                            return;
                        }

                        $criteria = QualityCriterion::whereHas('categories', fn ($q) => $q->where('categories.id', $categoryId))
                            ->where('is_active', true)
                            ->pluck('id');

                        $count = 0;
                        foreach ($criteria as $criterionId) {
                            $exists = QualityCheck::where('checkable_type', $owner->getMorphClass())
                                ->where('checkable_id', $owner->getKey())
                                ->where('quality_criterion_id', $criterionId)
                                ->exists();

                            if (! $exists) {
                                QualityCheck::create([
                                    'checkable_type' => $owner->getMorphClass(),
                                    'checkable_id' => $owner->getKey(),
                                    'quality_criterion_id' => $criterionId,
                                    'user_id' => auth()->id(),
                                    'status' => QualityCheckStatus::Pending->value,
                                ]);
                                $count++;
                            }
                        }

                        Notification::make()->title("{$count} Checks angelegt")->success()->send();
                    }),
                CreateAction::make()
                    ->label('Einzelnen Check hinzufügen')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['user_id'] ??= auth()->id();

                        return $data;
                    }),
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
