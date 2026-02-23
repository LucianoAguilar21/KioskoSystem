{{-- resources/views/products/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Producto</h1>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form method="POST" action="{{ route('products.update', $product) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Código -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Código
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code', $product->code) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code') border-red-500 @enderror"
                    >
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Rubro (Categoría) -->
<div>
    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
        Rubro
    </label>
    <select
        id="category_id"
        name="category_id"
        x-data="{ category: '{{ old('category_id', $product->category_id) }}' }"
        x-model="category"
        @change="$dispatch('category-changed', category)"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
    >
        <option value="">Sin rubro</option>
        @foreach(\App\Models\Category::active()->orderBy('name')->get() as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Línea -->
<div x-data="lineSelector()">
    <label for="line_id" class="block text-sm font-medium text-gray-700 mb-2">
        Línea
    </label>
    <select
        id="line_id"
        name="line_id"
        x-model="selectedLine"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('line_id') border-red-500 @enderror"
    >
        <option value="">Sin línea</option>
        <template x-for="line in filteredLines" :key="line.id">
            <option :value="line.id" x-text="line.name"></option>
        </template>
    </select>
    @error('line_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
function lineSelector() {
    return {
        selectedLine: '{{ old('line_id', $product->line_id) }}',
        selectedCategory: '{{ old('category_id', $product->category_id) }}',
        allLines: @json(\App\Models\Line::active()->with('category')->orderBy('name')->get()),

        get filteredLines() {
            if (!this.selectedCategory) {
                return this.allLines;
            }
            return this.allLines.filter(line => line.category_id == this.selectedCategory);
        },

        init() {
            this.$watch('selectedCategory', () => {
                const lineExists = this.filteredLines.find(line => line.id == this.selectedLine);
                if (!lineExists) {
                    this.selectedLine = '';
                }
            });

            window.addEventListener('category-changed', (e) => {
                this.selectedCategory = e.detail;
            });
        }
    }
}
</script>

<!-- Marca -->
<div>
    <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-2">
        Marca
    </label>
    <select
        id="brand_id"
        name="brand_id"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('brand_id') border-red-500 @enderror"
    >
        <option value="">Sin marca</option>
        @foreach(\App\Models\Brand::active()->orderBy('name')->get() as $brand)
            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>
    @error('brand_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

            <!-- Descripción -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                >{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Precio Costo -->
                <div>
                    <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Precio Costo <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input
                            type="number"
                            id="cost_price"
                            name="cost_price"
                            value="{{ old('cost_price', $product->cost_price) }}"
                            step="0.01"
                            min="0"
                            required
                            class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cost_price') border-red-500 @enderror"
                        >
                    </div>
                    @error('cost_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Precio Venta -->
                <div>
                    <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Precio Venta <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input
                            type="number"
                            id="sale_price"
                            name="sale_price"
                            value="{{ old('sale_price', $product->sale_price) }}"
                            step="0.01"
                            min="0"
                            required
                            class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sale_price') border-red-500 @enderror"
                        >
                    </div>
                    @error('sale_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                        Stock <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        value="{{ old('stock', $product->stock) }}"
                        min="0"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror"
                    >
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Mínimo -->
                <div>
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">
                        Stock Mínimo <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="min_stock"
                        name="min_stock"
                        value="{{ old('min_stock', $product->min_stock) }}"
                        min="0"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('min_stock') border-red-500 @enderror"
                    >
                    @error('min_stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Fecha de Vencimiento -->
            <div>
                <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Vencimiento
                </label>
                <input
                    type="date"
                    id="expires_at"
                    name="expires_at"
                    value="{{ old('expires_at', $product->expires_at?->format('Y-m-d')) }}"
                    min="{{ date('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('expires_at') border-red-500 @enderror"
                >
                @error('expires_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado -->
            <div>
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">Producto Activo</span>
                </label>
            </div>

            <!-- Historial de Precios -->
            @if($product->priceHistories->count() > 0)
                <div class="border-t pt-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Historial de Precios</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Fecha</th>
                                    <th class="px-4 py-2 text-left">Usuario</th>
                                    <th class="px-4 py-2 text-right">Costo Anterior</th>
                                    <th class="px-4 py-2 text-right">Costo Nuevo</th>
                                    <th class="px-4 py-2 text-right">Venta Anterior</th>
                                    <th class="px-4 py-2 text-right">Venta Nuevo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->priceHistories->take(10) as $history)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2">{{ $history->user->name }}</td>
                                        <td class="px-4 py-2 text-right">${{ number_format($history->old_cost_price, 2) }}</td>
                                        <td class="px-4 py-2 text-right font-semibold">${{ number_format($history->new_cost_price, 2) }}</td>
                                        <td class="px-4 py-2 text-right">${{ number_format($history->old_sale_price, 2) }}</td>
                                        <td class="px-4 py-2 text-right font-semibold">${{ number_format($history->new_sale_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Botones -->
            <div class="flex justify-between items-center pt-4 border-t">
                <div>
                    @can('delete', $product)
                        <button
                            type="button"
                            onclick="if(confirm('¿Está seguro de eliminar este producto?')) { document.getElementById('delete-form').submit(); }"
                            class="text-white bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg font-medium"
                        >
                            Eliminar
                        </button>
                    @endcan
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('products.index') }}" class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg font-medium">
                        Cancelar
                    </a>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 px-6 py-2 rounded-lg font-medium">
                        Actualizar
                    </button>
                </div>
            </div>
        </form>

        @can('delete', $product)
            <form id="delete-form" method="POST" action="{{ route('products.destroy', $product) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endcan
    </div>
</div>
@endsection
