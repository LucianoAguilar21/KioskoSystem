{{-- resources/views/sales/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="saleForm()">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Nueva Venta</h1>
        <a href="{{ route('sales.history') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Ver Historial
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Búsqueda y Productos -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Búsqueda -->
            <div class="bg-white shadow rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Producto</label>
                <input
                    type="text"
                    x-model="search"
                    @input.debounce.300ms="searchProducts()"
                    @keydown.enter.prevent="selectFirstResult()"
                    placeholder="Nombre o código... (Enter para seleccionar)"
                    class="w-full px-4 py-3 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    autofocus
                >

                <!-- Resultados de búsqueda -->
                <div x-show="searchResults.length > 0" class="mt-2 max-h-60 overflow-y-auto">
                    <template x-for="(product, index) in searchResults" :key="product.id">
                        <button
                            @click="addProduct(product)"
                            type="button"
                            :class="{'bg-blue-50': index === 0}"
                            class="w-full text-left p-3 hover:bg-gray-100 border-b flex justify-between items-center"
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

                                    <p class="font-semibold text-gray-900 w-24 text-right" x-text="'$' + (item.price * item.quantity).toFixed(2)"></p>

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
                            @change="resetPaymentAmounts()"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="cash">Efectivo</option>
                            <option value="card">Débito</option>
                            <option value="transfer">Transferencia / Mercado Pago</option>
                            <option value="mixed">Mixto</option>
                        </select>
                    </div>

                    <!-- Campos de pago mixto -->
                    <div x-show="paymentMethod === 'mixed'" class="space-y-3 border-t pt-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Efectivo</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input
                                    type="number"
                                    x-model.number="paymentAmounts.cash"
                                    @input="calculatePaymentStatus()"
                                    step="0.01"
                                    min="0"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Débito</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input
                                    type="number"
                                    x-model.number="paymentAmounts.card"
                                    @input="calculatePaymentStatus()"
                                    step="0.01"
                                    min="0"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Transferencia / MP</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input
                                    type="number"
                                    x-model.number="paymentAmounts.transfer"
                                    @input="calculatePaymentStatus()"
                                    step="0.01"
                                    min="0"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Pago único (no mixto) -->
                    <div x-show="paymentMethod !== 'mixed'" class="border-t pt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Monto Recibido</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input
                                type="number"
                                x-model.number="singlePaymentAmount"
                                @input="updateSinglePayment()"
                                step="0.01"
                                min="0"
                                class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 text-lg font-semibold focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <!-- Estado del pago -->
                    <div x-show="cart.length > 0" class="border-t pt-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Total Pagado:</span>
                            <span class="font-semibold" x-text="'$' + getTotalPaid().toFixed(2)"></span>
                        </div>

                        <!-- Badge de estado -->
                        <div x-show="paymentStatus.type" class="mt-2">
                            <div
                                :class="{
                                    'bg-red-100 text-red-800 border-red-200': paymentStatus.type === 'insufficient',
                                    'bg-green-100 text-green-800 border-green-200': paymentStatus.type === 'exact',
                                    'bg-yellow-100 text-yellow-800 border-yellow-200': paymentStatus.type === 'change'
                                }"
                                class="px-3 py-2 rounded-lg border text-center font-semibold"
                            >
                                <span x-text="paymentStatus.message"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Checkbox impresión automática -->
                    <div class="border-t pt-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                x-model="autoPrint"
                                @change="toggleAutoPrint()"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="ml-2 text-sm text-gray-700">Imprimir ticket automáticamente</span>
                        </label>
                    </div>

                    <!-- Botón confirmar -->
                    <button
                        @click="handleConfirmSale()"
                        type="button"
                        :disabled="cart.length === 0 || !canConfirmSale()"
                        :class="canConfirmSale() ? 'bg-green-700 hover:bg-green-800' : 'bg-gray-400 cursor-not-allowed'"
                        class="w-full text-white font-bold text-lg px-6 py-4 rounded-lg transition-colors"
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

    <!-- Modal de Confirmación de Vuelto -->
    <template x-teleport="body">
        <div
            x-show="showChangeModal"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div class="flex min-h-screen items-center justify-center p-4">
                <!-- Overlay -->
                <div
                    x-show="showChangeModal"
                    @click="showChangeModal = false"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="fixed inset-0 bg-gray-900 bg-opacity-75"
                ></div>

                <!-- Modal Content -->
                <div
                    x-show="showChangeModal"
                    x-transition
                    class="relative bg-white rounded-lg shadow-xl max-w-md w-full z-50"
                >
                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-yellow-100 rounded-full mb-4">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>

                        <h3 class="text-xl font-bold text-center text-gray-900 mb-4">
                            Confirmar Vuelto
                        </h3>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total de la venta:</span>
                                <span class="font-semibold" x-text="'$' + calculateTotal().toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total pagado:</span>
                                <span class="font-semibold" x-text="'$' + getTotalPaid().toFixed(2)"></span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-900 font-bold text-lg">Vuelto a entregar:</span>
                                    <span class="text-yellow-600 font-bold text-2xl" x-text="'$' + changeToGive.toFixed(2)"></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-yellow-800 text-center font-medium">
                                ⚠️ Debés entregar este monto en efectivo
                            </p>
                        </div>

                        <div class="flex space-x-3">
                            <button
                                @click="showChangeModal = false"
                                type="button"
                                class="flex-1 text-gray-700 bg-gray-200 hover:bg-gray-300 px-4 py-3 rounded-lg font-medium"
                            >
                                Cancelar
                            </button>
                            <button
                                @click="confirmSaleWithChange()"
                                type="button"
                                class="flex-1 text-white bg-green-700 hover:bg-green-800 px-4 py-3 rounded-lg font-medium"
                            >
                                Confirmar y Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function saleForm() {
    return {
        search: '',
        searchResults: [],
        cart: [],
        paymentMethod: 'cash',
        singlePaymentAmount: 0,
        paymentAmounts: {
            cash: 0,
            card: 0,
            transfer: 0
        },
        paymentStatus: {
            type: null,
            message: '',
            amount: 0
        },
        autoPrint: localStorage.getItem('autoPrintTicket') === 'true',
        showChangeModal: false,
        changeToGive: 0,

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

        selectFirstResult() {
            if (this.searchResults.length > 0) {
                this.addProduct(this.searchResults[0]);
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
            this.calculatePaymentStatus();
        },

        removeProduct(index) {
            this.cart.splice(index, 1);
            this.calculatePaymentStatus();
        },

        increaseQuantity(index) {
            if (this.cart[index].quantity < this.cart[index].stock) {
                this.cart[index].quantity++;
                this.calculatePaymentStatus();
            }
        },

        decreaseQuantity(index) {
            if (this.cart[index].quantity > 1) {
                this.cart[index].quantity--;
                this.calculatePaymentStatus();
            }
        },

        updateQuantity(index) {
            if (this.cart[index].quantity > this.cart[index].stock) {
                this.cart[index].quantity = this.cart[index].stock;
            }
            if (this.cart[index].quantity < 1) {
                this.cart[index].quantity = 1;
            }
            this.calculatePaymentStatus();
        },

        calculateTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        getTotalPaid() {
            if (this.paymentMethod === 'mixed') {
                return (this.paymentAmounts.cash || 0) + (this.paymentAmounts.card || 0) + (this.paymentAmounts.transfer || 0);
            } else {
                return this.singlePaymentAmount || 0;
            }
        },

        updateSinglePayment() {
            const key = this.paymentMethod === 'cash' ? 'cash' :
                       (this.paymentMethod === 'card' ? 'card' : 'transfer');

            this.paymentAmounts = { cash: 0, card: 0, transfer: 0 };
            this.paymentAmounts[key] = this.singlePaymentAmount || 0;
            this.calculatePaymentStatus();
        },

        resetPaymentAmounts() {
            this.singlePaymentAmount = 0;
            this.paymentAmounts = { cash: 0, card: 0, transfer: 0 };
            this.calculatePaymentStatus();
        },

        calculatePaymentStatus() {
            const total = this.calculateTotal();
            const paid = this.getTotalPaid();
            const difference = paid - total;

            if (paid < total - 0.01) {
                this.paymentStatus = {
                    type: 'insufficient',
                    message: `Faltan $${Math.abs(difference).toFixed(2)}`,
                    amount: difference
                };
            } else if (Math.abs(difference) < 0.01) {
                this.paymentStatus = {
                    type: 'exact',
                    message: 'Pago exacto ✓',
                    amount: 0
                };
            } else {
                this.paymentStatus = {
                    type: 'change',
                    message: `Vuelto: $${difference.toFixed(2)}`,
                    amount: difference
                };
            }
        },

        canConfirmSale() {
            if (this.cart.length === 0) return false;

            const total = this.calculateTotal();
            const paid = this.getTotalPaid();

            return paid >= total - 0.01;
        },

        handleConfirmSale() {
            if (!this.canConfirmSale()) {
                alert('El monto pagado es insuficiente');
                return;
            }

            const total = this.calculateTotal();
            const paid = this.getTotalPaid();
            const change = paid - total;

            // Si hay vuelto y hay efectivo, mostrar modal
            if (change > 0.01 && (this.paymentAmounts.cash || 0) > 0) {
                this.changeToGive = change;
                this.showChangeModal = true;
            } else {
                // Si no hay vuelto, confirmar directamente
                this.submitSale(0);
            }
        },

        confirmSaleWithChange() {
            this.showChangeModal = false;
            this.submitSale(this.changeToGive);
        },

        clearCart() {
            if (confirm('¿Está seguro de limpiar el carrito?')) {
                this.cart = [];
                this.paymentMethod = 'cash';
                this.singlePaymentAmount = 0;
                this.paymentAmounts = { cash: 0, card: 0, transfer: 0 };
                this.paymentStatus = { type: null, message: '', amount: 0 };
            }
        },

        toggleAutoPrint() {
            localStorage.setItem('autoPrintTicket', this.autoPrint);
        },

        async submitSale(changeAmount) {
            const items = this.cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity
            }));

            const data = {
                _token: document.querySelector('meta[name="csrf-token"]').content,
                payment_method: this.paymentMethod,
                items: items,
                payment_amounts: this.paymentAmounts,
                change_amount: changeAmount,
                total_paid: this.getTotalPaid(),
                auto_print: this.autoPrint
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
                    if (result.auto_print && result.ticket_url) {
                        window.open(result.ticket_url, '_blank');
                    }

                    this.cart = [];
                    this.paymentMethod = 'cash';
                    this.singlePaymentAmount = 0;
                    this.paymentAmounts = { cash: 0, card: 0, transfer: 0 };
                    this.paymentStatus = { type: null, message: '', amount: 0 };

                    alert(`Venta #${result.sale_id} registrada correctamente`);
                    setTimeout(() => window.location.reload(), 1000);
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

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
