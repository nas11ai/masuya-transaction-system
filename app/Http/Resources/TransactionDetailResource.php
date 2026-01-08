<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'qty' => $this->qty,
            'price' => (float) $this->price,
            'discount_amount' => (float) $this->discount_amount,
            'net_price' => (float) $this->net_price,
            'subtotal' => (float) $this->subtotal,
            'discounts' => TransactionDetailDiscountResource::collection($this->whenLoaded('discounts')),
        ];
    }
}
