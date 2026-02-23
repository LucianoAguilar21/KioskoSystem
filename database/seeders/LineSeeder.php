<?php
// database/seeders/LineSeeder.php

namespace Database\Seeders;

use App\Models\Line;
use App\Models\Category;
use Illuminate\Database\Seeder;

class LineSeeder extends Seeder
{
    public function run(): void
    {
        $bebidas = Category::where('name', 'Bebidas')->first();
        $snacks = Category::where('name', 'Snacks')->first();
        $almacen = Category::where('name', 'Almacén')->first();

        $lines = [
            // Bebidas
            ['category_id' => $bebidas->id, 'name' => 'Gaseosas', 'description' => 'Bebidas gaseosas'],
            ['category_id' => $bebidas->id, 'name' => 'Aguas', 'description' => 'Aguas minerales y saborizadas'],
            ['category_id' => $bebidas->id, 'name' => 'Jugos', 'description' => 'Jugos y néctares'],
            ['category_id' => $bebidas->id, 'name' => 'Cervezas', 'description' => 'Cervezas nacionales e importadas'],
            ['category_id' => $bebidas->id, 'name' => 'Vinos', 'description' => 'Vinos tintos, blancos y rosados'],
            ['category_id' => $bebidas->id, 'name' => 'Whisky', 'description' => 'Whiskys y bebidas espirituosas'],

            // Snacks
            ['category_id' => $snacks->id, 'name' => 'Papas Fritas', 'description' => 'Papas fritas y similares'],
            ['category_id' => $snacks->id, 'name' => 'Galletitas', 'description' => 'Galletitas dulces y saladas'],
            ['category_id' => $snacks->id, 'name' => 'Alfajores', 'description' => 'Alfajores de diferentes marcas'],
            ['category_id' => $snacks->id, 'name' => 'Chocolates', 'description' => 'Chocolates y bombones'],
            ['category_id' => $snacks->id, 'name' => 'Caramelos', 'description' => 'Caramelos y chicles'],

            // Almacén
            ['category_id' => $almacen->id, 'name' => 'Pastas', 'description' => 'Fideos y pastas'],
            ['category_id' => $almacen->id, 'name' => 'Arroz', 'description' => 'Arroz y legumbres'],
            ['category_id' => $almacen->id, 'name' => 'Aceites', 'description' => 'Aceites y condimentos'],
            ['category_id' => $almacen->id, 'name' => 'Lácteos', 'description' => 'Leche y derivados'],
        ];

        foreach ($lines as $line) {
            Line::create($line);
        }
    }
}
