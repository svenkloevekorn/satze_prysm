<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\SupplierContact;
use App\Models\SupplierProduct;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $textilesPro = Supplier::firstOrCreate(
            ['name' => 'Textiles Pro Portugal'],
            [
                'country' => 'PT',
                'address' => 'Rua das Fábricas 12, 4470 Maia, Portugal',
                'rating' => 9,
                'notes' => 'Spezialist für hochwertige Cycling-Bekleidung. Gute Kommunikation.',
                'is_active' => true,
            ],
        );

        $sofiaGarments = Supplier::firstOrCreate(
            ['name' => 'Sofia Garments'],
            [
                'country' => 'TR',
                'address' => 'Organize Sanayi Bölgesi, Istanbul',
                'rating' => 7,
                'notes' => 'Günstiger Preis, MOQ relativ hoch.',
                'is_active' => true,
            ],
        );

        SupplierContact::firstOrCreate(
            ['supplier_id' => $textilesPro->id, 'email' => 'maria@textilespro.pt'],
            ['name' => 'Maria Santos', 'phone' => '+351 912 345 678', 'role' => 'Key Account'],
        );
        SupplierContact::firstOrCreate(
            ['supplier_id' => $sofiaGarments->id, 'email' => 'ahmet@sofiagarments.com'],
            ['name' => 'Ahmet Yılmaz', 'phone' => '+90 212 555 10 20', 'role' => 'Sales Manager'],
        );

        $jerseys = Category::where('name', 'Cycling Jerseys')->first();
        $bibs = Category::where('name', 'Bib Shorts')->first();

        SupplierProduct::firstOrCreate(
            ['supplier_id' => $textilesPro->id, 'name' => 'Pro Summer Jersey PT-001'],
            [
                'category_id' => $jerseys?->id,
                'description' => 'Summer jersey, 4-way stretch, full zip, 3 rear pockets',
                'purchase_price' => 18.50,
                'recommended_retail_price' => 69.00,
                'moq' => 100,
                'materials' => ['Polyester', 'Lycra'],
                'colors' => ['Schwarz', 'Weiß', 'Rot', 'Blau'],
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'notes' => 'Custom-Print möglich ab 50 Stück pro Farbe',
            ],
        );

        SupplierProduct::firstOrCreate(
            ['supplier_id' => $sofiaGarments->id, 'name' => 'Race Bib SG-BIB-02'],
            [
                'category_id' => $bibs?->id,
                'description' => 'Race cut bib shorts with italian chamois',
                'purchase_price' => 24.00,
                'recommended_retail_price' => 89.00,
                'moq' => 200,
                'materials' => ['Nylon', 'Lycra'],
                'colors' => ['Schwarz'],
                'sizes' => ['S', 'M', 'L', 'XL'],
            ],
        );
    }
}
