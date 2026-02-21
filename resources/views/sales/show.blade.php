{{-- resources/views/sales/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Venta #{{ $sale->id }}</h1>
        <p class="mt-1 text-sm text-gray-600">
            {{ $sale->created_at->format('d/m/Y H:i') }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Productos -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">Producto</th>
                                <th class="px-6 py-3 text-right">Cantidad</th>
                                <th class="px-6 py-3 text-right">Precio</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                                <tr class="border-b">
                                    <td class="px-6 py-4">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4 text-right">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-semibold">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Información -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Información</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Vendedor:</span>
                        <span class="font-semibold">{{ $sale->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Caja:</span>
                        <span class="font-semibold">#{{ $sale->cash_register_session_id }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg sticky top-4">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Resumen</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Método de Pago</p>
                        @if($sale->payment_method === 'cash')
                            <x-badge variant="success">Efectivo</x-badge>
                        @elseif($sale->payment_method === 'card')
                            <x-badge variant="info">Tarjeta</x-badge>
                        @elseif($sale->payment_method === 'transfer')
                            <x-badge variant="default">Transferencia</x-badge>
                        @else
                            <x-badge variant="warning">Mixto</x-badge>
                        @endif
                    </div>

                    @if($sale->payment_method === 'mixed')
                        <div class="space-y-2 text-sm">
                            @if($sale->cash_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Efectivo:</span>
                                    <span>${{ number_format($sale->cash_amount, 2) }}</span>
                                </div>
                            @endif
                            @if($sale->card_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tarjeta:</span>
                                    <span>${{ number_format($sale->card_amount, 2) }}</span>
                                </div>
                            @endif
                            @if($sale->transfer_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Transferencia:</span>
                                    <span>${{ number_format($sale->transfer_amount, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="border-t pt-4">
                        <div class="flex justify-between text-xl">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span class="font-bold text-blue-600">${{ number_format($sale->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg mt-2">
                            <span class="font-bold text-gray-900">Vuelto:</span>
                            <span class=" bg-yellow-100 text-yellow-800 rounded px-1">${{ number_format($sale->change_amount, 2) }}</span>
                        </div>
                    </div>

                    {{-- <div class="pt-4 border-t">
                        <a href="{{ route('sales.history') }}" class="block w-full text-center text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg font-medium">
                            Volver al Historial
                        </a>
                    </div> --}}
                    <div class="pt-4 border-t space-y-2">
    <a
        href="{{ route('sales.ticket', $sale) }}"
        target="_blank"
        class="block w-full text-center text-white bg-green-700 hover:bg-green-800 px-4 py-2 rounded-lg font-medium"
    >
        Ver Ticket
    </a>
    <a
        href="{{ route('sales.download-ticket', $sale) }}"
        class="block w-full text-center text-gray-700 bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg font-medium"
    >
        Descargar PDF
    </a>
    <a
        href="{{ route('sales.history') }}"
        class="block w-full text-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-lg font-medium border border-blue-600"
    >
        Volver al Historial
    </a>
</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
