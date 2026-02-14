<?php
// database/seeders/SupplierSeeder.php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Distribuidora Central',
                'contact_name' => 'Juan Pérez',
                'phone' => '011-4567-8901',
                'email' => 'ventas@distribuidoracentral.com.ar',
                'address' => 'Av. Corrientes 1234, CABA',
                'is_active' => true,
            ],
            [
                'name' => 'Mayorista La Económica',
                'contact_name' => 'María González',
                'phone' => '011-4321-9876',
                'email' => 'contacto@laeconomica.com.ar',
                'address' => 'Calle Falsa 567, CABA',
                'is_active' => true,
            ],
            [
                'name' => 'Proveedor Regional',
                'contact_name' => 'Carlos Rodríguez',
                'phone' => '011-5555-6666',
                'email' => 'info@proveedorregional.com.ar',
                'address' => 'Av. Libertador 890, CABA',
                'is_active' => true,
            ],
            [
                'name' => 'Coca-Cola FEMSA',
                'contact_name' => 'Laura Martínez',
                'phone' => '0800-222-2653',
                'email' => 'ventas@cocacola.com.ar',
                'address' => 'Zona Industrial Norte',
                'is_active' => true,
            ],
            [
                'name' => 'Arcor Distribución',
                'contact_name' => 'Roberto Silva',
                'phone' => '0800-122-7267',
                'email' => 'distribuidores@arcor.com',
                'address' => 'Planta Arroyito, Córdoba',
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}