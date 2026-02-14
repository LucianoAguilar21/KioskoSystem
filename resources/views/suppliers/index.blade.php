{{-- resources/views/suppliers/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Proveedores</h1>
        <a href="{{ route('suppliers.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg font-medium">
            + Nuevo Proveedor
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Contacto</th>
                        <th class="px-6 py-3">Teléfono</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Compras</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $supplier->name }}
                            </td>
                            <td class="px-6 py-4">{{ $supplier->contact_name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $supplier->email ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <x-badge variant="info">{{ $supplier->purchases_count }}</x-badge>
                            </td>
                            <td class="px-6 py-4">
                                @if($supplier->is_active)
                                    <x-badge variant="success">Activo</x-badge>
                                @else
                                    <x-badge variant="danger">Inactivo</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="text-blue-600 hover:text-blue-800">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No hay proveedores registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection