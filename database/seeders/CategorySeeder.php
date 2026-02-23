<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bebidas',
                'description' => 'Bebidas alcohólicas y no alcohólicas',
                'is_active' => true,
            ],
            [
                'name' => 'Snacks',
                'description' => 'Golosinas, papas fritas, galletitas',
                'is_active' => true,
            ],
            [
                'name' => 'Almacén',
                'description' => 'Productos de almacén y despensa',
                'is_active' => true,
            ],
            [
                'name' => 'Limpieza',
                'description' => 'Productos de limpieza e higiene del hogar',
                'is_active' => true,
            ],
            [
                'name' => 'Higiene Personal',
                'description' => 'Productos de cuidado personal',
                'is_active' => true,
            ],
            [
                'name' => 'Cigarrillos',
                'description' => 'Cigarrillos y productos de tabaquería',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
