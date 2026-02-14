{{-- resources/views/cash-register/open-required.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center py-12">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        <h2 class="mt-6 text-3xl font-bold text-gray-900">Caja Cerrada</h2>
        <p class="mt-2 text-gray-600">Debe abrir caja antes de realizar ventas</p>
        <div class="mt-8">
            <a href="{{ route('cash-register.index') }}" class="text-white bg-blue-700 hover:bg-blue-800 px-8 py-3 rounded-lg font-medium text-lg">
                Ir a Caja
            </a>
        </div>
    </div>
</div>
@endsection