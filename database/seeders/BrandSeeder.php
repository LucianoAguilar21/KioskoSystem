<?php
// database/seeders/BrandSeeder.php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            // Bebidas
            ['name' => 'Coca Cola', 'description' => 'The Coca-Cola Company'],
            ['name' => 'Pepsi', 'description' => 'PepsiCo'],
            ['name' => 'Sprite', 'description' => 'The Coca-Cola Company'],
            ['name' => 'Fanta', 'description' => 'The Coca-Cola Company'],
            ['name' => 'Quilmes', 'description' => 'Cervecería Quilmes'],
            ['name' => 'Andes', 'description' => 'Cerveza Andes'],
            ['name' => 'Baggio', 'description' => 'Jugos Baggio'],

            // Snacks
            ['name' => 'Lays', 'description' => 'Frito-Lay'],
            ['name' => 'Doritos', 'description' => 'Frito-Lay'],
            ['name' => 'Pepitos', 'description' => 'Arcor'],
            ['name' => 'Oreo', 'description' => 'Mondelez'],
            ['name' => 'Jorgito', 'description' => 'Georgalos'],
            ['name' => 'Milka', 'description' => 'Mondelez'],
            ['name' => 'Sugus', 'description' => 'Arcor'],
            ['name' => 'Beldent', 'description' => 'Mondelez'],

            // Cigarrillos
            ['name' => 'Marlboro', 'description' => 'Philip Morris'],
            ['name' => 'Philip Morris', 'description' => 'Philip Morris'],
            ['name' => 'Camel', 'description' => 'R.J. Reynolds'],

            // Almacén
            ['name' => 'Bimbo', 'description' => 'Grupo Bimbo'],
            ['name' => 'La Serenísima', 'description' => 'Mastellone Hnos.'],
            ['name' => 'Hellmanns', 'description' => 'Unilever'],
            ['name' => 'Cocinero', 'description' => 'Molinos Río de la Plata'],

            // Limpieza
            ['name' => 'Magistral', 'description' => 'Unilever'],
            ['name' => 'Ayudín', 'description' => 'Unilever'],
            ['name' => 'Higienol', 'description' => 'Papel Prensa'],
            ['name' => 'Dove', 'description' => 'Unilever'],
            ['name' => 'Sedal', 'description' => 'Unilever'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
