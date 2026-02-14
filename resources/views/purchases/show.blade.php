{{-- resources/views/purchases/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Compra #{{ $purchase->id }}</h1>
        <p class="mt-1 text-sm text-gray-600">
            Registrada el {{ $purchase->created_at->format('d/m/Y H:i') }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información General -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Detalles -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Información de la Compra</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Proveedor:</span>
                        <span class="font-semibold">{{ $purchase->supplier->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fecha de Compra:</span>
                        <span class="font-semibold">{{ $purchase->purchase_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Número de Factura:</span>
                        <span class="font-semibold">{{ $purchase->invoice_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Registrado por:</span>
                        <span class="font-semibold">{{ $purchase->user->name }}</span>
                    </div>
                    @if($purchase->notes)
                        <div class="pt-3 border-t">
                            <p class="text-gray-600 text-sm mb-1">Notas:</p>
                            <p class="text-gray-900">{{ $purchase->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Productos -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">Producto</th>
                                <th class="px-6 py-3 text-right">Cantidad</th>
                                <th class="px-6 py-3 text-right">Costo Unit.</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $item)
                                <tr class="border-b">
                                    <td class="px-6 py-4">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4 text-right">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right">${{ number_format($item->unit_cost, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-semibold">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Resumen -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg sticky top-4">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Resumen</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Productos:</span>
                            <span class="font-semibold">{{ $purchase->items->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Unidades:</span>
                            <span class="font-semibold">{{ $purchase->items->sum('quantity') }}</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-xl">
                                <span class="font-bold text-gray-900">Total:</span>
                                <span class="font-bold text-blue-600">${{ number_format($purchase->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <a href="{{ route('purchases.index') }}" class="block w-full text-center text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg font-medium">
                            Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection