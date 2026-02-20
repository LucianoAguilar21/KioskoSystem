<?php
// app/Models/Sale.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cash_register_session_id',
        'total_amount',
        'total_paid',
        'change_amount',
        'payment_method',
        'cash_amount',
        'card_amount',
        'transfer_amount',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'total_paid' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'cash_amount' => 'decimal:2',
            'card_amount' => 'decimal:2',
            'transfer_amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashRegisterSession()
    {
        return $this->belongsTo(CashRegisterSession::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Helpers
    public function totalProfit(): float
    {
        return $this->items->sum(function ($item) {
            return ($item->unit_price - $item->product->cost_price) * $item->quantity;
        });
    }

    public function hasChange(): bool
    {
        return $this->change_amount > 0;
    }

    public function netCashAmount(): float
    {
        // Efectivo real que queda en caja (efectivo recibido - vuelto entregado)
        return $this->cash_amount - $this->change_amount;
    }
}
