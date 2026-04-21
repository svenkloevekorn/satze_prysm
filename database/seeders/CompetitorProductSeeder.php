<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CompetitorProduct;
use App\Models\ProductShopEntry;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompetitorProductSeeder extends Seeder
{
    public function run(): void
    {
        $castelli = Brand::firstOrCreate(['name' => 'Castelli'], ['is_active' => true]);
        $rapha = Brand::firstOrCreate(['name' => 'Rapha'], ['is_active' => true]);
        $assos = Brand::firstOrCreate(['name' => 'Assos'], ['is_active' => true]);

        $jerseys = Category::where('name', 'Cycling Jerseys')->first();
        $bibs = Category::where('name', 'Bib Shorts')->first();

        $products = [
            [
                'name' => 'Castelli Climber Jersey',
                'brand_id' => $castelli->id,
                'category_id' => $jerseys?->id,
                'description' => 'Leichtes Sommer-Trikot für heiße Tage und Bergetappen.',
                'materials' => ['Polyester', 'Lycra'],
                'colors' => ['Schwarz', 'Weiß', 'Rot'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'price_min' => 89.00,
                'price_max' => 119.00,
                'overall_rating' => 8,
                'positives' => 'Sehr atmungsaktiv, leichtes Material, gute Passform',
                'negatives' => 'Etwas teuer, Reißverschluss könnte robuster sein',
            ],
            [
                'name' => 'Rapha Pro Team Bib Shorts II',
                'brand_id' => $rapha->id,
                'category_id' => $bibs?->id,
                'description' => 'Premium Bib Shorts mit Pro-Polster.',
                'materials' => ['Nylon', 'Lycra'],
                'colors' => ['Schwarz'],
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'price_min' => 220.00,
                'price_max' => 260.00,
                'overall_rating' => 9,
                'positives' => 'Hervorragender Komfort auch auf langen Touren',
                'negatives' => 'Premium-Preis',
            ],
            [
                'name' => 'Assos Mille GT Jersey',
                'brand_id' => $assos->id,
                'category_id' => $jerseys?->id,
                'description' => 'Allround-Trikot für lange Distanzen.',
                'materials' => ['Polyester'],
                'colors' => ['Schwarz', 'Blau'],
                'sizes' => ['S', 'M', 'L'],
                'price_min' => 110.00,
                'price_max' => 140.00,
                'overall_rating' => 7,
                'positives' => 'Solide Verarbeitung, vielseitig einsetzbar',
                'negatives' => 'Schnitt fällt klein aus',
            ],
        ];

        foreach ($products as $data) {
            $product = CompetitorProduct::firstOrCreate(['name' => $data['name']], $data);

            $shops = Shop::limit(2)->get();
            foreach ($shops as $i => $shop) {
                ProductShopEntry::firstOrCreate(
                    ['competitor_product_id' => $product->id, 'shop_id' => $shop->id],
                    [
                        'product_url' => 'https://example.com/'.Str::slug($data['name']),
                        'observed_price' => $data['price_min'] + ($i * 5),
                        'observed_at' => now()->subDays($i * 7),
                        'notes' => $i === 0 ? 'Aktuelle Beobachtung' : 'Vor einer Woche',
                    ],
                );
            }
        }
    }
}
