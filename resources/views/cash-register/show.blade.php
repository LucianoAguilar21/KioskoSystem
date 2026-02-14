{{-- resources/views/cash-register/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detalle de Caja #{{ $session->id }}</h1>
        <p class="mt-1 text-sm text-gray-600">
            {{ $session->opened_at->format('d/m/Y H:i') }} - 
            {{ $session->closed_at ? $session->closed_at->format('d/m/Y H:i') : 'Abierta' }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Resumen -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Resumen</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Usuario</p>
                        <p class="font-semibold text-gray-900">{{ $session->user->name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Estado</p>
                        @if($session->isOpen())
                            <x-badge variant="success">Abierta</x-badge>
                        @else
                            <x-badge variant="default">Cerrada</x-badge>
                        @endif
                    </div>

                    <div class="border-t pt-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fondo Inicial:</span>
                            <span class="font-semibold">${{ number_format($session->initial_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Ventas:</span>
                            <span class="font-semibold">{{ $summary['total_sales'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Monto Total:</span>
                            <span class="font-semibold">${{ number_format($summary['total_amount'], 2) }}</span>
                        </div>
                    </div>

                    <div class="border-t pt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Efectivo:</span>
                            <span>${{ number_format($summary['cash_sales'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tarjeta:</span>
                            <span>${{ number_format($summary['card_sales'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Transferencia:</span>
                            <span>${{ number_format($summary['transfer_sales'], 2) }}</span>
                        </div>
                    </div>

                    @if($session->isClosed())
                        <div class="border-t pt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Efectivo Esperado:</span>
                                <span class="font-semibold">${{ number_format($session->expected_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Efectivo Contado:</span>
                                <span class="font-semibold">${{ number_format($session->final_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-900 font-bold">Diferencia:</span>
                                <span class="font-bold {{ $session->difference == 0 ? 'text-green-600' : ($session->difference > 0 ? 'text-blue-600' : 'text-red-600') }}">
                                    ${{ number_format($session->difference, 2) }}
                                </span>
                            </div>
                        </div>

                        @if($session->notes)
                            <div class="border-t pt-4">
                                <p class="text-sm text-gray-600 mb-1">Notas:</p>
                                <p class="text-gray-900 text-sm">{{ $session->notes }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Ventas -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Ventas Realizadas</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Hora</th>
                                <th class="px-6 py-3">Productos</th>
                                <th class="px-6 py-3">Método</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($session->sales as $sale)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">#{{ $sale->id }}</td>
                                    <td class="px-6 py-4">{{ $sale->created_at->format('H:i') }}</td>
                                    <td class="px-6 py-4">{{ $sale->items->count() }}</td>
                                    <td class="px-6 py-4">
                                        @if($sale->payment_method === 'cash')
                                            <x-badge variant="success">Efectivo</x-badge>
                                        @elseif($sale->payment_method === 'card')
                                            <x-badge variant="info">Tarjeta</x-badge>
                                        @elseif($sale->payment_method === 'transfer')
                                            <x-badge variant="default">Transfer.</x-badge>
                                        @else
                                            <x-badge variant="warning">Mixto</x-badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold">${{ number_format($sale->total_amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        No hay ventas en esta caja
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t flex justify-between items-center">
                    <a href="{{ route('cash-register.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        ← Volver al Listado
                    </a>
                    @if($session->isOpen() && auth()->user()->can('close', $session))
                        <a href="{{ route('cash-register.close.form', $session) }}" class="text-white bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg font-medium">
                            Cerrar Caja
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection