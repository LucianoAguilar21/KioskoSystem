<?php
// app/Services/StockService.php
namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class StockService
{
    public function getLowStockProducts(): Collection
    {
        return Product::active()
            ->lowStock()
            ->orderBy('stock', 'asc')
            ->get();
    }

    public function getExpiringProducts(int $days = 7): Collection
    {
        return Product::active()
            ->expiring($days)
            ->orderBy('expires_at', 'asc')
            ->get();
    }

    public function getCriticalAlerts(): array
    {
        return [
            'low_stock' => $this->getLowStockProducts(),
            'expiring_soon' => $this->getExpiringProducts(7),
            'expired' => $this->getExpiredProducts(),
        ];
    }

    public function getExpiredProducts(): Collection
    {
        return Product::active()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();
    }

    public function updateStock(Product $product, int $quantity, string $type = 'adjustment'): void
    {
        if ($type === 'increment') {
            $product->increment('stock', $quantity);
        } elseif ($type === 'decrement') {
            $product->decrement('stock', $quantity);
        } else {
            $product->update(['stock' => $quantity]);
        }
    }
}