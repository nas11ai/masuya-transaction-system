<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'product_code',
        'product_name',
        'qty',
        'price',
        'discount_amount',
        'net_price',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'net_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    // Relationships
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function discounts()
    {
        return $this->hasMany(TransactionDetailDiscount::class)->orderBy('sequence');
    }

    // Helper Methods
    public function calculateNetPrice(): float
    {
        return round($this->price - $this->discount_amount, 2);
    }

    public function calculateSubtotal(): float
    {
        return round($this->net_price * $this->qty, 2);
    }
}
