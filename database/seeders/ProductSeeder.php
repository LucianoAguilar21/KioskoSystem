<?php
// database/seeders/ProductSeeder.php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Line;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // Categorías
        // =========================
        $bebidas     = Category::where('name', 'Bebidas')->first();
        $snacks      = Category::where('name', 'Snacks')->first();
        $almacen     = Category::where('name', 'Almacén')->first();
        $limpieza    = Category::where('name', 'Limpieza')->first();
        $higiene     = Category::where('name', 'Higiene Personal')->first();
        $cigarrillos = Category::where('name', 'Cigarrillos')->first();

        // =========================
        // Líneas
        // =========================
        $gaseosas   = Line::where('name', 'Gaseosas')->first();
        $aguas      = Line::where('name', 'Aguas')->first();
        $jugos      = Line::where('name', 'Jugos')->first();
        $cervezas   = Line::where('name', 'Cervezas')->first();

        $papas      = Line::where('name', 'Papas Fritas')->first();
        $galletitas = Line::where('name', 'Galletitas')->first();
        $alfajores  = Line::where('name', 'Alfajores')->first();
        $chocolates = Line::where('name', 'Chocolates')->first();
        $caramelos  = Line::where('name', 'Caramelos')->first();

        $pastas     = Line::where('name', 'Pastas')->first();
        $lacteos    = Line::where('name', 'Lácteos')->first();
        $aceites    = Line::where('name', 'Aceites')->first();

        // =========================
        // Marcas
        // =========================
        $cocaCola   = Brand::where('name', 'Coca Cola')->first();
        $sprite     = Brand::where('name', 'Sprite')->first();
        $fanta      = Brand::where('name', 'Fanta')->first();
        $baggio     = Brand::where('name', 'Baggio')->first();
        $quilmes    = Brand::where('name', 'Quilmes')->first();

        $lays       = Brand::where('name', 'Lays')->first();
        $doritos    = Brand::where('name', 'Doritos')->first();
        $oreo       = Brand::where('name', 'Oreo')->first();
        $jorgito    = Brand::where('name', 'Jorgito')->first();
        $milka      = Brand::where('name', 'Milka')->first();
        $sugus      = Brand::where('name', 'Sugus')->first();
        $beldent    = Brand::where('name', 'Beldent')->first();

        $marlboro   = Brand::where('name', 'Marlboro')->first();
        $philip     = Brand::where('name', 'Philip Morris')->first();
        $camel      = Brand::where('name', 'Camel')->first();

        $bimbo      = Brand::where('name', 'Bimbo')->first();
        $serenisima = Brand::where('name', 'La Serenísima')->first();
        $hellmanns  = Brand::where('name', 'Hellmanns')->first();

        $magistral  = Brand::where('name', 'Magistral')->first();
        $higienol   = Brand::where('name', 'Higienol')->first();
        $dove       = Brand::where('name', 'Dove')->first();
        $sedal      = Brand::where('name', 'Sedal')->first();

        // =========================
        // Productos
        // =========================
        $products = [

            // ===== BEBIDAS =====
            [
                'code' => 'BEB001',
                'name' => 'Coca Cola 500ml',
                'description' => 'Gaseosa Coca Cola botella 500ml',
                'category_id' => $bebidas->id,
                'line_id' => $gaseosas->id,
                'brand_id' => $cocaCola->id,
                'cost_price' => 120,
                'sale_price' => 180,
                'stock' => 50,
                'min_stock' => 10,
                'is_active' => true,
            ],
            [
                'code' => 'BEB002',
                'name' => 'Sprite 500ml',
                'description' => 'Gaseosa Sprite botella 500ml',
                'category_id' => $bebidas->id,
                'line_id' => $gaseosas->id,
                'brand_id' => $sprite->id,
                'cost_price' => 115,
                'sale_price' => 175,
                'stock' => 45,
                'min_stock' => 10,
                'is_active' => true,
            ],
            [
                'code' => 'BEB003',
                'name' => 'Jugo Baggio 1L',
                'description' => 'Jugo Baggio multifruta',
                'category_id' => $bebidas->id,
                'line_id' => $jugos->id,
                'brand_id' => $baggio->id,
                'cost_price' => 180,
                'sale_price' => 280,
                'stock' => 35,
                'min_stock' => 8,
                'is_active' => true,
            ],
            [
                'code' => 'BEB004',
                'name' => 'Cerveza Quilmes 1L',
                'description' => 'Cerveza Quilmes litro',
                'category_id' => $bebidas->id,
                'line_id' => $cervezas->id,
                'brand_id' => $quilmes->id,
                'cost_price' => 350,
                'sale_price' => 550,
                'stock' => 60,
                'min_stock' => 15,
                'is_active' => true,
            ],

            // ===== SNACKS =====
            [
                'code' => 'SNK001',
                'name' => 'Lays Clásicas 150g',
                'description' => 'Papas fritas Lays clásicas',
                'category_id' => $snacks->id,
                'line_id' => $papas->id,
                'brand_id' => $lays->id,
                'cost_price' => 220,
                'sale_price' => 350,
                'stock' => 40,
                'min_stock' => 10,
                'is_active' => true,
            ],
            [
                'code' => 'SNK002',
                'name' => 'Oreo 118g',
                'description' => 'Galletitas Oreo clásicas',
                'category_id' => $snacks->id,
                'line_id' => $galletitas->id,
                'brand_id' => $oreo->id,
                'cost_price' => 180,
                'sale_price' => 280,
                'stock' => 50,
                'min_stock' => 12,
                'is_active' => true,
            ],
            [
                'code' => 'SNK003',
                'name' => 'Alfajor Milka',
                'description' => 'Alfajor Milka triple',
                'category_id' => $snacks->id,
                'line_id' => $alfajores->id,
                'brand_id' => $milka->id,
                'cost_price' => 180,
                'sale_price' => 280,
                'stock' => 45,
                'min_stock' => 12,
                'is_active' => true,
            ],

            // ===== CIGARRILLOS =====
            [
                'code' => 'CIG001',
                'name' => 'Marlboro Box',
                'description' => 'Cigarrillos Marlboro caja x20',
                'category_id' => $cigarrillos->id,
                'line_id' => null,
                'brand_id' => $marlboro->id,
                'cost_price' => 850,
                'sale_price' => 1200,
                'stock' => 100,
                'min_stock' => 20,
                'is_active' => true,
            ],

            // ===== ALMACÉN =====
            [
                'code' => 'ALM001',
                'name' => 'Pan Lactal',
                'description' => 'Pan lactal Bimbo grande',
                'category_id' => $almacen->id,
                'line_id' => $pastas->id,
                'brand_id' => $bimbo->id,
                'cost_price' => 280,
                'sale_price' => 420,
                'stock' => 20,
                'min_stock' => 5,
                'is_active' => true,
            ],

            // ===== LIMPIEZA / HIGIENE =====
            [
                'code' => 'HIG001',
                'name' => 'Detergente Magistral 500ml',
                'description' => 'Detergente líquido Magistral',
                'category_id' => $limpieza->id,
                'line_id' => null,
                'brand_id' => $magistral->id,
                'cost_price' => 320,
                'sale_price' => 480,
                'stock' => 25,
                'min_stock' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
