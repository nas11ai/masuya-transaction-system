<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product' => [
                'code' => $this->product?->code,
                'name' => $this->product?->name,
            ],
            'transaction_id' => $this->transaction_id,
            'type' => $this->type,
            'qty' => $this->qty,
            'stock_before' => $this->stock_before,
            'stock_after' => $this->stock_after,
            'reference_no' => $this->reference_no,
            'notes' => $this->notes,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user?->name,
            ],
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
