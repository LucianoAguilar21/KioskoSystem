<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'cost_price',
        'sale_price',
        'stock',
        'min_stock',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock' => 'integer',
            'min_stock' => 'integer',
            'expires_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }

    public function scopeExpiring($query, $days = 7)
    {
        return $query->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    // Helpers
    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function profitMargin(): float
    {
        if ($this->cost_price == 0) return 0;
        return (($this->sale_price - $this->cost_price) / $this->cost_price) * 100;
    }

    public function profitAmount(): float
    {
        return $this->sale_price - $this->cost_price;
    }
}