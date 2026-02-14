{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">
            {{ now()->format('l, d F Y') }}
        </p>
    </div>

    <!-- Caja Actual -->
    @if($currentSession)
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-blue-900">Caja Abierta</h3>
                    <p class="text-sm text-blue-700">
                        Apertura: {{ $currentSession->opened_at->format('H:i') }} - 
                        Fondo inicial: ${{ number_format($currentSession->initial_amount, 2) }}
                    </p>
                </div>
                <a href="{{ route('cash-register.close.form', $currentSession) }}" class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg text-sm font-medium">
                    Cerrar Caja
                </a>
            </div>
        </div>
    @else
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-yellow-800">No hay caja abierta. <a href="{{ route('cash-register.index') }}" class="font-semibold underline">Abrir caja</a></p>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Total Ventas -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ventas Hoy</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $todayStats['total_sales'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Ingresos -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Ingresos</dt>
                            <dd class="text-lg font-semibold text-gray-900">${{ number_format($todayStats['total_amount'], 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ganancia Estimada -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ganancia Estimada</dt>
                            <dd class="text-lg font-semibold text-gray-900">${{ number_format($todayStats['estimated_profit'], 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Efectivo -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Efectivo</dt>
                            <dd class="text-lg font-semibold text-gray-900">${{ number_format($todayStats['cash_amount'], 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Productos Más Vendidos -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Productos Más Vendidos Hoy</h3>
                
                @if(count($topProducts) > 0)
                    <div class="space-y-3">
                        @foreach($topProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $product->total_quantity }} unidades</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">${{ number_format($product->total_amount, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No hay ventas registradas hoy</p>
                @endif
            </div>
        </div>

        <!-- Alertas de Stock -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Alertas de Stock</h3>
                
                @if($stockAlerts['low_stock']->count() > 0)
                    <div class="space-y-2 mb-4">
                        <h4 class="text-sm font-semibold text-red-800">Stock Bajo</h4>
                        @foreach($stockAlerts['low_stock']->take(5) as $product)
                            <div class="flex items-center justify-between p-2 bg-red-50 rounded">
                                <span class="text-sm text-gray-900">{{ $product->name }}</span>
                                <x-badge variant="danger">{{ $product->stock }} unidades</x-badge>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($stockAlerts['expiring_soon']->count() > 0)
                    <div class="space-y-2 mb-4">
                        <h4 class="text-sm font-semibold text-yellow-800">Por Vencer</h4>
                        @foreach($stockAlerts['expiring_soon']->take(5) as $product)
                            <div class="flex items-center justify-between p-2 bg-yellow-50 rounded">
                                <span class="text-sm text-gray-900">{{ $product->name }}</span>
                                <x-badge variant="warning">{{ $product->expires_at->diffForHumans() }}</x-badge>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($stockAlerts['expired']->count() > 0)
                    <div class="space-y-2">
                        <h4 class="text-sm font-semibold text-red-800">Vencidos</h4>
                        @foreach($stockAlerts['expired']->take(5) as $product)
                            <div class="flex items-center justify-between p-2 bg-red-50 rounded">
                                <span class="text-sm text-gray-900">{{ $product->name }}</span>
                                <x-badge variant="danger">Vencido</x-badge>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($stockAlerts['low_stock']->count() == 0 && $stockAlerts['expiring_soon']->count() == 0 && $stockAlerts['expired']->count() == 0)
                    <p class="text-gray-500 text-center py-4">No hay alertas de stock</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection