<?php
// app/Services/SaleService.php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use App\Models\CashRegisterSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleService
{
    public function createSale(
        User $user,
        CashRegisterSession $session,
        array $items,
        string $paymentMethod,
        array $paymentAmounts,
        float $changeAmount = 0,
        float $totalPaid = 0
    ): Sale {
        if ($session->isClosed()) {
            throw new \Exception('No se puede vender con caja cerrada');
        }

        DB::beginTransaction();
        try {
            // Validar stock
            $this->validateStock($items);

            // Calcular total
            $totalAmount = $this->calculateTotal($items);

            // Si no se envió totalPaid, calcularlo
            if ($totalPaid === 0) {
                $totalPaid = ($paymentAmounts['cash'] ?? 0) +
                            ($paymentAmounts['card'] ?? 0) +
                            ($paymentAmounts['transfer'] ?? 0);
            }

            // Validar montos de pago
            $this->validatePaymentAmounts($paymentMethod, $paymentAmounts, $totalAmount, $totalPaid, $changeAmount);

            // Crear venta
            $sale = Sale::create([
                'user_id' => $user->id,
                'cash_register_session_id' => $session->id,
                'total_amount' => $totalAmount,
                'total_paid' => $totalPaid,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'cash_amount' => $paymentAmounts['cash'] ?? 0,
                'card_amount' => $paymentAmounts['card'] ?? 0,
                'transfer_amount' => $paymentAmounts['transfer'] ?? 0,
            ]);

            // Crear items y descontar stock
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->sale_price,
                    'subtotal' => $product->sale_price * $item['quantity'],
                ]);

                // Descontar stock
                $product->decrement('stock', $item['quantity']);
            }

            Log::info('Venta registrada', [
                'sale_id' => $sale->id,
                'total' => $totalAmount,
                'total_paid' => $totalPaid,
                'change' => $changeAmount,
                'items_count' => count($items),
            ]);

            DB::commit();
            return $sale->load('items.product');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear venta', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function validateStock(array $items): void
    {
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->stock < $item['quantity']) {
                throw new \Exception(
                    "Stock insuficiente para {$product->name}. Disponible: {$product->stock}"
                );
            }
        }
    }

    private function calculateTotal(array $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $total += $product->sale_price * $item['quantity'];
        }
        return $total;
    }

    private function validatePaymentAmounts(
        string $method,
        array $amounts,
        float $total,
        float $totalPaid,
        float $changeAmount
    ): void {
        // Calcular el pago efectivo (total pagado - vuelto)
        $effectivePayment = $totalPaid - $changeAmount;

        // El pago efectivo debe ser igual o mayor al total
        if ($effectivePayment < $total - 0.01) {
            throw new \Exception('El monto pagado es insuficiente');
        }

        // Si hay vuelto, debe haber pago en efectivo
        if ($changeAmount > 0 && ($amounts['cash'] ?? 0) == 0) {
            throw new \Exception('No se puede dar vuelto sin pago en efectivo');
        }

        // El vuelto no puede ser mayor al efectivo recibido
        if ($changeAmount > ($amounts['cash'] ?? 0)) {
            throw new \Exception('El vuelto no puede ser mayor al efectivo recibido');
        }

        if ($method !== 'mixed') {
            $key = $method === 'cash' ? 'cash' :
                   ($method === 'card' ? 'card' : 'transfer');

            if (($amounts[$key] ?? 0) == 0) {
                throw new \Exception('Debe ingresar un monto para el método de pago seleccionado');
            }
        }
    }
}
