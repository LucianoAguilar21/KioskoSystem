{{-- resources/views/cash-register/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Caja</h1>
    </div>

    <!-- Estado Actual -->
    @if($currentSession)
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-green-900">Caja Abierta</h3>
                    <div class="mt-2 space-y-1 text-sm text-green-700">
                        <p><strong>Apertura:</strong> {{ $currentSession->opened_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Usuario:</strong> {{ $currentSession->user->name }}</p>
                        <p><strong>Fondo Inicial:</strong> ${{ number_format($currentSession->initial_amount, 2) }}</p>
                    </div>
                </div>
                <a 
                    href="{{ route('cash-register.close.form', $currentSession) }}" 
                    class="text-white bg-green-700 hover:bg-green-800 px-6 py-3 rounded-lg font-medium"
                >
                    Cerrar Caja
                </a>
            </div>
        </div>
    @else
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-6" x-data="{ showModal: false }">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-blue-900">No hay caja abierta</h3>
                    <p class="mt-1 text-sm text-blue-700">Debe abrir caja para poder realizar ventas</p>
                </div>
                <button 
                    @click="showModal = true"
                    class="text-white bg-blue-700 hover:bg-blue-800 px-6 py-3 rounded-lg font-medium"
                >
                    Abrir Caja
                </button>
            </div>

            <!-- Modal Abrir Caja -->
            <div 
                x-show="showModal"
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto"
                style="display: none;"
            >
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div 
                        x-show="showModal"
                        @click="showModal = false"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="fixed inset-0 bg-gray-900 bg-opacity-50"
                    ></div>

                    <div 
                        x-show="showModal"
                        x-transition
                        class="relative bg-white rounded-lg shadow-xl max-w-md w-full"
                    >
                        <form method="POST" action="{{ route('cash-register.open') }}">
                            @csrf
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Abrir Caja</h3>
                                
                                <div>
                                    <label for="initial_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                        Monto Inicial <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">$</span>
                                        <input 
                                            type="number" 
                                            id="initial_amount" 
                                            name="initial_amount" 
                                            step="0.01"
                                            min="0"
                                            required
                                            autofocus
                                            class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 text-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Ingrese el monto de efectivo inicial en caja</p>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 px-6 py-4 bg-gray-50 rounded-b-lg">
                                <button 
                                    type="button"
                                    @click="showModal = false"
                                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg font-medium"
                                >
                                    Cancelar
                                </button>
                                <button 
                                    type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 px-6 py-2 rounded-lg font-medium"
                                >
                                    Abrir Caja
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Historial de Cajas -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Historial de Cajas</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Apertura</th>
                        <th class="px-6 py-3">Cierre</th>
                        <th class="px-6 py-3">Usuario</th>
                        <th class="px-6 py-3">Inicial</th>
                        <th class="px-6 py-3">Final</th>
                        <th class="px-6 py-3">Diferencia</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $session->opened_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                {{ $session->closed_at ? $session->closed_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4">{{ $session->user->name }}</td>
                            <td class="px-6 py-4">${{ number_format($session->initial_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                {{ $session->final_amount ? '$' . number_format($session->final_amount, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($session->difference !== null)
                                    <x-badge :variant="$session->difference == 0 ? 'success' : ($session->difference > 0 ? 'info' : 'danger')">
                                        ${{ number_format($session->difference, 2) }}
                                    </x-badge>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($session->status === 'open')
                                    <x-badge variant="success">Abierta</x-badge>
                                @else
                                    <x-badge variant="default">Cerrada</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($session->status === 'closed')
                                    <a href="{{ route('cash-register.show', $session) }}" class="text-blue-600 hover:text-blue-800">
                                        Ver Detalles
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                No hay registros de caja
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection