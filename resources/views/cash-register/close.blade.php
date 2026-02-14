{{-- resources/views/cash-register/close.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Cerrar Caja</h1>
        <p class="mt-1 text-sm text-gray-600">Caja abierta el {{ $session->opened_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Resumen de Ventas -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Resumen del Día</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total de Ventas:</span>
                    <span class="font-semibold">{{ $summary['total_sales'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Monto Total:</span>
                    <span class="font-semibold">${{ number_format($summary['total_amount'], 2) }}</span>
                </div>
                <div class="border-t pt-3 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Efectivo:</span>
                        <span class="font-medium">${{ number_format($summary['cash_sales'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tarjeta:</span>
                        <span class="font-medium">${{ number_format($summary['card_sales'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Transferencia:</span>
                        <span class="font-medium">${{ number_format($summary['transfer_sales'], 2) }}</span>
                    </div>
                </div>
                <div class="border-t pt-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fondo Inicial:</span>
                        <span class="font-semibold">${{ number_format($summary['initial_amount'], 2) }}</span>
                    </div>
                    <div class="flex justify-between mt-2 text-lg">
                        <span class="text-gray-900 font-bold">Efectivo Esperado:</span>
                        <span class="font-bold text-blue-600">${{ number_format($summary['expected_cash'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Cierre -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Cierre de Caja</h3>
            </div>
            <form method="POST" action="{{ route('cash-register.close', $session) }}" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="final_amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Efectivo en Caja <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500 text-lg">$</span>
                        <input 
                            type="number" 
                            id="final_amount" 
                            name="final_amount" 
                            step="0.01"
                            min="0"
                            required
                            autofocus
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-xl font-bold focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('final_amount') border-red-500 @enderror"
                        >
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Ingrese el monto total de efectivo contado en caja</p>
                    @error('final_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notas (opcional)
                    </label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4"
                        placeholder="Observaciones, faltantes, sobrantes..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                    >{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t pt-4 flex justify-end space-x-3">
                    <a href="{{ route('cash-register.index') }}" class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg font-medium">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        onclick="return confirm('¿Está seguro de cerrar la caja? Esta acción no se puede deshacer.')"
                        class="text-white bg-red-700 hover:bg-red-800 px-6 py-2 rounded-lg font-medium"
                    >
                        Cerrar Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection