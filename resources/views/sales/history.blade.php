{{-- resources/views/sales/history.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Historial de Ventas</h1>
        <a href="{{ route('sales.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Volver a Ventas
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Fecha/Hora</th>
                        <th class="px-6 py-3">Usuario</th>
                        <th class="px-6 py-3">Productos</th>
                        <th class="px-6 py-3">Pago</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">#{{ $sale->id }}</td>
                            <td class="px-6 py-4">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">{{ $sale->user->name }}</td>
                            <td class="px-6 py-4">{{ $sale->items->count() }} productos</td>
                            <td class="px-6 py-4">
                                @if($sale->payment_method === 'cash')
                                    <x-badge variant="success">Efectivo</x-badge>
                                @elseif($sale->payment_method === 'card')
                                    <x-badge variant="info">Tarjeta</x-badge>
                                @elseif($sale->payment_method === 'transfer')
                                    <x-badge variant="default">Transferencia</x-badge>
                                @else
                                    <x-badge variant="warning">Mixto</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
    <div class="flex space-x-3">
        <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800">
            Ver
        </a>
        <a href="{{ route('sales.ticket', $sale) }}" target="_blank" class="text-green-600 hover:text-green-800">
            Ticket
        </a>
        <a href="{{ route('sales.download-ticket', $sale) }}" class="text-gray-600 hover:text-gray-800">
            Descargar
        </a>
    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No hay ventas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection