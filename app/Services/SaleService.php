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
        array $paymentAmounts
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

            // Validar montos de pago
            $this->validatePaymentAmounts($paymentMethod, $paymentAmounts, $totalAmount);

            // Crear venta
            $sale = Sale::create([
                'user_id' => $user->id,
                'cash_register_session_id' => $session->id,
                'total_amount' => $totalAmount,
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
        float $total
    ): void {
        $sum = ($amounts['cash'] ?? 0) + 
               ($amounts['card'] ?? 0) + 
               ($amounts['transfer'] ?? 0);

        if (abs($sum - $total) > 0.01) {
            throw new \Exception('Los montos de pago no coinciden con el total');
        }

        if ($method !== 'mixed') {
            $key = $method === 'cash' ? 'cash' : 
                   ($method === 'card' ? 'card' : 'transfer');
            
            if (abs(($amounts[$key] ?? 0) - $total) > 0.01) {
                throw new \Exception('Monto de pago incorrecto para método seleccionado');
            }
        }
    }
}