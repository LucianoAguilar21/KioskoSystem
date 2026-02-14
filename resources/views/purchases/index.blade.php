{{-- resources/views/purchases/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Compras</h1>
        <a href="{{ route('purchases.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg font-medium">
            + Nueva Compra
        </a>
    </div>

    <!-- Tabla de Compras -->
    <div class="bg-white shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Fecha</th>
                        <th class="px-6 py-3">Proveedor</th>
                        <th class="px-6 py-3">Factura</th>
                        <th class="px-6 py-3">Usuario</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                #{{ $purchase->id }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $purchase->purchase_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">{{ $purchase->supplier->name }}</td>
                            <td class="px-6 py-4">{{ $purchase->invoice_number ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $purchase->user->name }}</td>
                            <td class="px-6 py-4 font-semibold">
                                ${{ number_format($purchase->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('purchases.show', $purchase) }}" class="text-blue-600 hover:text-blue-800">
                                    Ver Detalles
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No hay compras registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection