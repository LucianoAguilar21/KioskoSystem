{{-- resources/views/purchases/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Nueva Compra</h1>
    </div>

    <div x-data="purchaseForm()" class="bg-white shadow rounded-lg">
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Proveedor -->
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Proveedor <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="supplier_id"
                        x-model="form.supplier_id"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Seleccione un proveedor</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="purchase_date"
                        x-model="form.purchase_date"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Factura -->
                <div>
                    <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Factura (opcional)
                    </label>
                    <input
                        type="text"
                        id="invoice_number"
                        x-model="form.invoice_number"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>

            <!-- Productos -->
            <div class="border-t pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
                    <button
                        type="button"
                        @click="addItem()"
                        class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg text-sm font-medium"
                    >
                        + Agregar Producto
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in form.items" :key="index">
                        <div class="grid grid-cols-12 gap-3 p-3 bg-gray-50 rounded-lg items-center">
                            <!-- Producto -->
                            <div class="col-span-7">
                                <select
                                    x-model="item.product_id"
                                    @change="onProductSelect(index)"
                                    required
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                                >
                                    <option value="">Seleccione producto</option>
                                    <template x-for="product in products" :key="product.id">
                                        <option
                                            :value="product.id"
                                            x-text="product.name + ' (Stock: ' + product.stock + ') - $' + parseFloat(product.cost_price).toFixed(2)"
                                        ></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Cantidad -->
                            <div class="col-span-2">
                                <input
                                    type="number"
                                    x-model.number="item.quantity"
                                    @input="calculateSubtotal(index)"
                                    placeholder="Cantidad"
                                    min="1"
                                    required
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                >
                            </div>

                            <!-- Subtotal -->
                            <div class="col-span-2">
                                <input
                                    type="text"
                                    :value="'$' + (item.subtotal || 0).toFixed(2)"
                                    readonly
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-gray-100 font-semibold text-right"
                                >
                            </div>

                            <!-- Eliminar -->
                            <div class="col-span-1 flex items-center justify-center">
                                <button
                                    type="button"
                                    @click="removeItem(index)"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <template x-if="form.items.length === 0">
                        <p class="text-gray-500 text-center py-4">No hay productos agregados</p>
                    </template>
                </div>

                <!-- Total -->
                <div class="mt-4 flex justify-end">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total de la Compra</p>
                        <p class="text-2xl font-bold text-blue-600" x-text="'$' + calculateTotal().toFixed(2)"></p>
                    </div>
                </div>
            </div>

            <!-- Notas -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notas (opcional)
                </label>
                <textarea
                    id="notes"
                    x-model="form.notes"
                    rows="3"
                    placeholder="Observaciones sobre la compra..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('purchases.index') }}" class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg font-medium">
                    Cancelar
                </a>
                <button
                    type="button"
                    @click="submitForm()"
                    :disabled="form.items.length === 0 || !form.supplier_id"
                    class="text-white bg-blue-700 hover:bg-blue-800 disabled:bg-gray-400 disabled:cursor-not-allowed px-6 py-2 rounded-lg font-medium"
                >
                    Registrar Compra
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function purchaseForm() {
    return {
        form: {
            supplier_id: '',
            purchase_date: '{{ date("Y-m-d") }}',
            invoice_number: '',
            items: [],
            notes: ''
        },

        products: {!! json_encode($products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'cost_price' => (float) $product->cost_price,
                'stock' => $product->stock,
            ];
        })->values()) !!},

        addItem() {
            this.form.items.push({
                product_id: '',
                quantity: 1,
                unit_cost: 0,
                subtotal: 0
            });
        },

        removeItem(index) {
            this.form.items.splice(index, 1);
        },

        onProductSelect(index) {
            const item = this.form.items[index];
            const product = this.products.find(p => p.id == item.product_id);

            if (product) {
                item.unit_cost = parseFloat(product.cost_price);
                this.calculateSubtotal(index);
            }
        },

        calculateSubtotal(index) {
            const item = this.form.items[index];
            item.subtotal = (item.quantity || 0) * (item.unit_cost || 0);
        },

        calculateTotal() {
            return this.form.items.reduce((sum, item) => sum + (item.subtotal || 0), 0);
        },

        async submitForm() {
            if (this.form.items.length === 0) {
                alert('Debe agregar al menos un producto');
                return;
            }

            if (!this.form.supplier_id) {
                alert('Debe seleccionar un proveedor');
                return;
            }

            for (let i = 0; i < this.form.items.length; i++) {
                const item = this.form.items[i];
                if (!item.product_id) {
                    alert(`Debe seleccionar un producto en la fila ${i + 1}`);
                    return;
                }
                if (!item.quantity || item.quantity < 1) {
                    alert(`Debe ingresar una cantidad válida en la fila ${i + 1}`);
                    return;
                }
            }

            const data = {
                _token: document.querySelector('meta[name="csrf-token"]').content,
                supplier_id: this.form.supplier_id,
                purchase_date: this.form.purchase_date,
                invoice_number: this.form.invoice_number || null,
                notes: this.form.notes || null,
                items: this.form.items
            };

            try {
                const response = await fetch('{{ route("purchases.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    window.location.href = '{{ route("purchases.index") }}';
                } else {
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('\n');
                        alert('Errores de validación:\n' + errorMessages);
                    } else {
                        alert(result.message || 'Error al registrar la compra');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al registrar la compra');
            }
        }
    }
}
</script>
@endsection
