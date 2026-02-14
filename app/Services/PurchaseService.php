<?php

// app/Services/PurchaseService.php
namespace App\Services;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseService
{
    public function createPurchase(
        User $user,
        Supplier $supplier,
        array $items,
        ?string $invoiceNumber = null,
        ?\DateTime $purchaseDate = null,
        ?string $notes = null
    ): Purchase {
        DB::beginTransaction();
        try {
            $totalAmount = $this->calculateTotal($items);

            $purchase = Purchase::create([
                'supplier_id' => $supplier->id,
                'user_id' => $user->id,
                'invoice_number' => $invoiceNumber,
                'total_amount' => $totalAmount,
                'purchase_date' => $purchaseDate ?? now(),
                'notes' => $notes,
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Crear item de compra
                $purchase->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'subtotal' => $item['unit_cost'] * $item['quantity'],
                ]);

                // Actualizar stock
                $product->increment('stock', $item['quantity']);

                // Actualizar costo si cambió
                if ($product->cost_price != $item['unit_cost']) {
                    $this->updateProductCost($product, $item['unit_cost'], $user);
                }
            }

            Log::info('Compra registrada', [
                'purchase_id' => $purchase->id,
                'supplier_id' => $supplier->id,
                'total' => $totalAmount,
            ]);

            DB::commit();
            return $purchase->load('items.product');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar compra', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function calculateTotal(array $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['unit_cost'] * $item['quantity'];
        }
        return $total;
    }

    private function updateProductCost(Product $product, float $newCost, User $user): void
    {
        $product->priceHistories()->create([
            'user_id' => $user->id,
            'old_cost_price' => $product->cost_price,
            'new_cost_price' => $newCost,
            'old_sale_price' => $product->sale_price,
            'new_sale_price' => $product->sale_price,
        ]);

        $product->update(['cost_price' => $newCost]);
    }
}