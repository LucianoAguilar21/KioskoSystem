{{-- resources/views/products/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Nuevo Producto</h1>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form method="POST" action="{{ route('products.store') }}" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Código -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Código (opcional)
                    </label>
                    <input 
                        type="text" 
                        id="code" 
                        name="code" 
                        value="{{ old('code') }}"
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
                        value="{{ old('name') }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
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
                >{{ old('description') }}</textarea>
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
                            value="{{ old('cost_price') }}"
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
                            value="{{ old('sale_price') }}"
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
                <!-- Stock Inicial -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                        Stock Inicial <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="stock" 
                        name="stock" 
                        value="{{ old('stock', 0) }}"
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
                        value="{{ old('min_stock', 5) }}"
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
                    Fecha de Vencimiento (opcional)
                </label>
                <input 
                    type="date" 
                    id="expires_at" 
                    name="expires_at" 
                    value="{{ old('expires_at') }}"
                    min="{{ date('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('expires_at') border-red-500 @enderror"
                >
                @error('expires_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('products.index') }}" class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg font-medium">
                    Cancelar
                </a>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 px-6 py-2 rounded-lg font-medium">
                    Crear Producto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection