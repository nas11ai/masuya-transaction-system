<?php

namespace App\Models;

use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetailDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_detail_id',
        'discount_type_id',
        'sequence',
        'discount_name',
        'discount_type',
        'discount_value',
        'discount_amount',
    ];

    protected function casts(): array
    {
        return [
            'sequence' => 'integer',
            'discount_value' => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function transactionDetail()
    {
        return $this->belongsTo(TransactionDetail::class);
    }

    public function discountType()
    {
        return $this->belongsTo(DiscountType::class);
    }
}
