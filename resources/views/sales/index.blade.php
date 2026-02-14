{{-- resources/views/sales/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Nueva Venta</h1>
        
        <a href="{{ route('sales.history') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Ver Historial
        </a>
    </div>

    <div x-data="saleForm()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Búsqueda y Productos -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Búsqueda -->
            <div class="bg-white shadow rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Producto</label>
                <input 
                    type="text"
                    x-model="search"
                    @input.debounce.300ms="searchProducts()"
                    placeholder="Nombre o código..."
                    class="w-full px-4 py-3 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    autofocus
                >

                <!-- Resultados de búsqueda -->
                <div x-show="searchResults.length > 0" class="mt-2 max-h-60 overflow-y-auto">
                    <template x-for="product in searchResults" :key="product.id">
                        <button 
                            @click="addProduct(product)"
                            type="button"
                            class="w-full text-left p-3 hover:bg-gray-50 border-b flex justify-between items-center"
                        >
                            <div>
                                <p class="font-medium" x-text="product.name"></p>
                                <p class="text-sm text-gray-500" x-text="'Stock: ' + product.stock"></p>
                            </div>
                            <p class="font-semibold text-blue-600" x-text="'$' + parseFloat(product.sale_price).toFixed(2)"></p>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Productos en el carrito -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Productos en Venta</h3>
                </div>
                <div class="p-4">
                    <template x-if="cart.length === 0">
                        <p class="text-gray-500 text-center py-8">No hay productos agregados</p>
                    </template>

                    <div x-show="cart.length > 0" class="space-y-2">
                        <template x-for="(item, index) in cart" :key="index">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900" x-text="item.name"></p>
                                    <p class="text-sm text-gray-500" x-text="'$' + parseFloat(item.price).toFixed(2) + ' c/u'"></p>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <!-- Cantidad -->
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            @click="decreaseQuantity(index)"
                                            type="button"
                                            class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded text-gray-700 font-bold"
                                        >-</button>
                                        <input 
                                            type="number"
                                            x-model.number="item.quantity"
                                            @change="updateQuantity(index)"
                                            min="1"
                                            :max="item.stock"
                                            class="w-16 text-center border border-gray-300 rounded"
                                        >
                                        <button 
                                            @click="increaseQuantity(index)"
                                            type="button"
                                            class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded text-gray-700 font-bold"
                                        >+</button>
                                    </div>
                                    
                                    <!-- Subtotal -->
                                    <p class="font-semibold text-gray-900 w-24 text-right" x-text="'$' + (item.price * item.quantity).toFixed(2)"></p>
                                    
                                    <!-- Eliminar -->
                                    <button 
                                        @click="removeProduct(index)"
                                        type="button"
                                        class="text-red-600 hover:text-red-800"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen y Pago -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg sticky top-4">
                <div class="px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Resumen</h3>
                </div>
                
                <div class="p-4 space-y-4">
                    <!-- Total -->
                    <div class="flex justify-between items-center text-2xl font-bold">
                        <span>TOTAL:</span>
                        <span class="text-blue-600" x-text="'$' + calculateTotal().toFixed(2)"></span>
                    </div>

                    <!-- Método de pago -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                        <select 
                            x-model="paymentMethod"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="cash">Efectivo</option>
                            <option value="card">Tarjeta</option>
                            <option value="transfer">Transferencia</option>
                            <option value="mixed">Mixto</option>
                        </select>
                    </div>

                    <!-- Campos de pago mixto -->
                    <div x-show="paymentMethod === 'mixed'" class="space-y-2">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Efectivo</label>
                            <input 
                                type="number"
                                x-model.number="paymentAmounts.cash"
                                step="0.01"
                                min="0"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                            >
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Tarjeta</label>
                            <input 
                                type="number"
                                x-model.number="paymentAmounts.card"
                                step="0.01"
                                min="0"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                            >
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Transferencia</label>
                            <input 
                                type="number"
                                x-model.number="paymentAmounts.transfer"
                                step="0.01"
                                min="0"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                            >
                        </div>
                    </div>

                    <!-- Botón confirmar -->
                    <button 
                        @click="submitSale()"
                        type="button"
                        :disabled="cart.length === 0"
                        class="w-full text-white bg-green-700 hover:bg-green-800 disabled:bg-gray-400 disabled:cursor-not-allowed font-bold text-lg px-6 py-4 rounded-lg transition-colors"
                    >
                        CONFIRMAR VENTA
                    </button>

                    <!-- Botón limpiar -->
                    <button 
                        @click="clearCart()"
                        type="button"
                        class="w-full text-gray-700 bg-gray-200 hover:bg-gray-300 font-medium px-4 py-2 rounded-lg"
                    >
                        Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function saleForm() {
    return {
        search: '',
        searchResults: [],
        cart: [],
        paymentMethod: 'cash',
        paymentAmounts: {
            cash: 0,
            card: 0,
            transfer: 0
        },

        async searchProducts() {
            if (this.search.length < 2) {
                this.searchResults = [];
                return;
            }

            try {
                const response = await fetch(`/sales/search?q=${encodeURIComponent(this.search)}`);
                this.searchResults = await response.json();
            } catch (error) {
                console.error('Error searching products:', error);
            }
        },

        addProduct(product) {
            const existingIndex = this.cart.findIndex(item => item.id === product.id);
            
            if (existingIndex >= 0) {
                if (this.cart[existingIndex].quantity < product.stock) {
                    this.cart[existingIndex].quantity++;
                }
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.sale_price),
                    quantity: 1,
                    stock: product.stock
                });
            }

            this.search = '';
            this.searchResults = [];
        },

        removeProduct(index) {
            this.cart.splice(index, 1);
        },

        increaseQuantity(index) {
            if (this.cart[index].quantity < this.cart[index].stock) {
                this.cart[index].quantity++;
            }
        },

        decreaseQuantity(index) {
            if (this.cart[index].quantity > 1) {
                this.cart[index].quantity--;
            }
        },

        updateQuantity(index) {
            if (this.cart[index].quantity > this.cart[index].stock) {
                this.cart[index].quantity = this.cart[index].stock;
            }
            if (this.cart[index].quantity < 1) {
                this.cart[index].quantity = 1;
            }
        },

        calculateTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        clearCart() {
            if (confirm('¿Está seguro de limpiar el carrito?')) {
                this.cart = [];
                this.paymentMethod = 'cash';
                this.paymentAmounts = { cash: 0, card: 0, transfer: 0 };
            }
        },

        async submitSale() {
            if (this.cart.length === 0) {
                alert('No hay productos en el carrito');
                return;
            }

            const total = this.calculateTotal();
            let amounts = { cash: 0, card: 0, transfer: 0 };

            if (this.paymentMethod === 'mixed') {
                amounts = { ...this.paymentAmounts };
                const sum = amounts.cash + amounts.card + amounts.transfer;
                if (Math.abs(sum - total) > 0.01) {
                    alert('Los montos de pago no coinciden con el total');
                    return;
                }
            } else {
                amounts[this.paymentMethod] = total;
            }

            const items = this.cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity
            }));

            const data = {
                _token: document.querySelector('meta[name="csrf-token"]').content,
                payment_method: this.paymentMethod,
                items: items,
                payment_amounts: amounts
            };

            try {
                const response = await fetch('{{ route("sales.store") }}', {
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
                    window.location.reload();
                } else {
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('\n');
                        alert('Errores de validación:\n' + errorMessages);
                    } else {
                        alert(result.message || 'Error al procesar la venta');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar la venta');
            }
        }
    }
}
</script>
@endsection