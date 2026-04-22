<?php

namespace Database\Seeders;

use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\FinalProduct;
use App\Models\SupplierProduct;
use Illuminate\Database\Seeder;

class PlaceholderImagesSeeder extends Seeder
{
    /** Farb-Paletten pro Produkt-Typ (Hex ohne #) */
    protected array $colorPalette = [
        'competitor' => ['d97706', 'b45309', 'f59e0b', 'ea580c', 'dc2626'],  // Orange/Rot
        'supplier' => ['0ea5e9', '0284c7', '0369a1', '0891b2', '0e7490'],    // Blau/Cyan
        'final' => ['10b981', '059669', '047857', '16a34a', '15803d'],      // Grün
        'development' => ['8b5cf6', '7c3aed', '6d28d9', 'a855f7', '9333ea'], // Lila
    ];

    public function run(): void
    {
        $this->seedFor(CompetitorProduct::all(), 'competitor');
        $this->seedFor(SupplierProduct::all(), 'supplier');
        $this->seedFor(FinalProduct::all(), 'final');
        $this->seedFor(DevelopmentItem::all(), 'development');
    }

    protected function seedFor($collection, string $paletteKey): void
    {
        foreach ($collection as $product) {
            if ($product->getMedia('images')->isNotEmpty()) {
                continue; // hat schon ein Bild
            }

            $color = $this->colorPalette[$paletteKey][$product->id % count($this->colorPalette[$paletteKey])];
            $label = $product->name ?? 'Produkt';
            $svg = $this->placeholderSvg($color, $label);

            $filename = 'placeholder-'.$paletteKey.'-'.$product->id.'.svg';
            $tmpPath = storage_path('app/tmp-'.$filename);
            file_put_contents($tmpPath, $svg);

            $product
                ->addMedia($tmpPath)
                ->usingFileName($filename)
                ->toMediaCollection('images');

            @unlink($tmpPath);
        }
    }

    protected function placeholderSvg(string $hex, string $label): string
    {
        $safeLabel = htmlspecialchars(mb_substr($label, 0, 40), ENT_XML1);

        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
  <rect width="600" height="600" fill="#{$hex}"/>
  <text x="50%" y="50%" fill="#ffffff" font-family="system-ui, sans-serif"
        font-size="28" text-anchor="middle" dominant-baseline="middle">
    {$safeLabel}
  </text>
</svg>
SVG;
    }
}
