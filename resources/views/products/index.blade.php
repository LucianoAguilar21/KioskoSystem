{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Productos</h1>
        @can('create', App\Models\Product::class)
            <a href="{{ route('products.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg font-medium">
                + Nuevo Producto
            </a>
        @endcan
    </div>

    <!-- Filtros -->
    <!-- Filtros -->
<div class="mb-6 bg-white shadow rounded-lg p-4">
    <form method="GET" action="{{ route('products.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Búsqueda -->
            <div>
                <input
                    type="text"
                    name="search"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="Buscar por nombre o código..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2"
                >
            </div>

            <!-- Rubro -->
            <div>
                <select
                    name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2"
                >
                    <option value="">Todos los rubros</option>
                    @foreach(\App\Models\Category::active()->orderBy('name')->get() as $category)
                        <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Marca -->
            <div>
                <select
                    name="brand_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2"
                >
                    <option value="">Todas las marcas</option>
                    @foreach(\App\Models\Brand::active()->orderBy('name')->get() as $brand)
                        <option value="{{ $brand->id }}" {{ ($filters['brand_id'] ?? '') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Stock bajo -->
            <div class="flex items-center">
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        name="low_stock"
                        value="1"
                        {{ ($filters['low_stock'] ?? false) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">Solo stock bajo</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 px-6 py-2 rounded-lg font-medium">
                Buscar
            </button>
            @if(array_filter($filters ?? []))
                <a href="{{ route('products.index') }}" class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg font-medium">
                    Limpiar
                </a>
            @endif
        </div>
    </form>
</div>

    <!-- Tabla -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
    <tr>
        <th class="px-6 py-3">Código</th>
        <th class="px-6 py-3">Nombre</th>
        <th class="px-6 py-3">Rubro</th>
        <th class="px-6 py-3">Marca</th>
        <th class="px-6 py-3">Precio Costo</th>
        <th class="px-6 py-3">Precio Venta</th>
        <th class="px-6 py-3">Margen</th>
        <th class="px-6 py-3">Stock</th>
        <th class="px-6 py-3">Estado</th>
        <th class="px-6 py-3">Acciones</th>
    </tr>
</thead>
<tbody>
    @forelse($products as $product)
        <tr class="bg-white border-b hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-900">
                {{ $product->code ?? '-' }}
            </td>
            <td class="px-6 py-4">{{ $product->name }}</td>
            <td class="px-6 py-4">
                @if($product->category)
                    <x-badge variant="info">{{ $product->category->name }}</x-badge>
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </td>
            <td class="px-6 py-4">
                {{ $product->brand->name ?? '-' }}
            </td>
            <td class="px-6 py-4">${{ number_format($product->cost_price, 2) }}</td>
            <td class="px-6 py-4 font-semibold">${{ number_format($product->sale_price, 2) }}</td>
            <td class="px-6 py-4">
                <x-badge :variant="$product->profitMargin() > 30 ? 'success' : 'warning'">
                    {{ number_format($product->profitMargin(), 1) }}%
                </x-badge>
            </td>
            <td class="px-6 py-4">
                @if($product->isLowStock())
                    <x-badge variant="danger">{{ $product->stock }}</x-badge>
                @else
                    <x-badge variant="success">{{ $product->stock }}</x-badge>
                @endif
            </td>
            <td class="px-6 py-4">
                @if($product->is_active)
                    <x-badge variant="success">Activo</x-badge>
                @else
                    <x-badge variant="danger">Inactivo</x-badge>
                @endif
            </td>
            <td class="px-6 py-4">
                <div class="flex space-x-2">
                    @can('update', $product)
                        <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:text-blue-800">
                            Editar
                        </a>
                    @endcan
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                No hay productos registrados
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($products->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
