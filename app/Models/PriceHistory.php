<?php

// app/Models/PriceHistory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'old_cost_price',
        'new_cost_price',
        'old_sale_price',
        'new_sale_price',
    ];

    protected function casts(): array
    {
        return [
            'old_cost_price' => 'decimal:2',
            'new_cost_price' => 'decimal:2',
            'old_sale_price' => 'decimal:2',
            'new_sale_price' => 'decimal:2',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
