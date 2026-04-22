<?php

namespace App\Filament\Pages;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\FinalProduct;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MedienGalerie extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.medien-galerie';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $title = 'Medien-Galerie';

    protected static ?string $navigationLabel = 'Medien-Galerie';

    protected static string|\UnitEnum|null $navigationGroup = 'Medien';

    protected static ?int $navigationSort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(Media::query()->latest('created_at'))
            ->contentGrid([
                'md' => 3,
                'xl' => 5,
            ])
            ->defaultPaginationPageOption(24)
            ->emptyStateHeading('Noch keine Medien')
            ->emptyStateDescription('Bilder werden automatisch hier angezeigt, sobald du sie an einem Produkt oder Entwicklungs-Item hochlädst.')
            ->emptyStateIcon('heroicon-o-photo')
            ->columns([
                ImageColumn::make('preview')
                    ->label('')
                    ->state(fn (Media $record) => $record->getFullUrl())
                    ->height(180)
                    ->square(),
                TextColumn::make('model_type')
                    ->label('Quelle')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        CompetitorProduct::class, 'competitor_product' => 'Wettbewerber',
                        SupplierProduct::class, 'supplier_product' => 'Lieferant',
                        FinalProduct::class, 'final_product' => 'Eigenes Produkt',
                        DevelopmentItem::class, 'development_item' => 'Entwicklung',
                        default => class_basename($state),
                    })
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        CompetitorProduct::class, 'competitor_product' => 'warning',
                        SupplierProduct::class, 'supplier_product' => 'info',
                        FinalProduct::class, 'final_product' => 'success',
                        DevelopmentItem::class, 'development_item' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('host_name')
                    ->label('Gehört zu')
                    ->state(function (Media $record): string {
                        $model = $record->model;

                        return $model && isset($model->name) ? (string) $model->name : '—';
                    })
                    ->weight('bold')
                    ->wrap(),
                TextColumn::make('name')
                    ->label('Datei')
                    ->limit(24),
            ])
            ->filters([
                SelectFilter::make('model_type')
                    ->label('Quelle')
                    ->options([
                        CompetitorProduct::class => 'Wettbewerbsprodukte',
                        SupplierProduct::class => 'Lieferanten-Produkte',
                        FinalProduct::class => 'Eigene Produkte',
                        DevelopmentItem::class => 'Entwicklungs-Items',
                    ]),
                SelectFilter::make('brand')
                    ->label('Marke (nur Wettbewerb)')
                    ->options(fn () => Brand::orderBy('name')->pluck('name', 'id'))
                    ->query(function ($query, array $data) {
                        if (! ($data['value'] ?? null)) {
                            return $query;
                        }

                        $productIds = CompetitorProduct::where('brand_id', $data['value'])->pluck('id');

                        return $query
                            ->where('model_type', CompetitorProduct::class)
                            ->whereIn('model_id', $productIds);
                    }),
                SelectFilter::make('supplier')
                    ->label('Lieferant (nur Lieferantenprodukte)')
                    ->options(fn () => Supplier::orderBy('name')->pluck('name', 'id'))
                    ->query(function ($query, array $data) {
                        if (! ($data['value'] ?? null)) {
                            return $query;
                        }

                        $productIds = SupplierProduct::where('supplier_id', $data['value'])->pluck('id');

                        return $query
                            ->where('model_type', SupplierProduct::class)
                            ->whereIn('model_id', $productIds);
                    }),
                SelectFilter::make('category')
                    ->label('Kategorie')
                    ->options(fn () => Category::orderBy('name')->pluck('name', 'id'))
                    ->query(function ($query, array $data) {
                        if (! ($data['value'] ?? null)) {
                            return $query;
                        }

                        $catId = $data['value'];

                        return $query->where(function ($q) use ($catId) {
                            $q->where(function ($sub) use ($catId) {
                                $sub->where('model_type', CompetitorProduct::class)
                                    ->whereIn('model_id', CompetitorProduct::where('category_id', $catId)->pluck('id'));
                            })
                                ->orWhere(function ($sub) use ($catId) {
                                    $sub->where('model_type', SupplierProduct::class)
                                        ->whereIn('model_id', SupplierProduct::where('category_id', $catId)->pluck('id'));
                                })
                                ->orWhere(function ($sub) use ($catId) {
                                    $sub->where('model_type', FinalProduct::class)
                                        ->whereIn('model_id', FinalProduct::where('category_id', $catId)->pluck('id'));
                                })
                                ->orWhere(function ($sub) use ($catId) {
                                    $sub->where('model_type', DevelopmentItem::class)
                                        ->whereIn('model_id', DevelopmentItem::where('category_id', $catId)->pluck('id'));
                                });
                        });
                    }),
            ], layout: FiltersLayout::AboveContent);
    }
}
