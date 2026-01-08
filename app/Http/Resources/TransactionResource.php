<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_no' => $this->invoice_no,
            'invoice_date' => $this->invoice_date->toDateString(),
            'status' => $this->status,
            'subtotal' => (float) $this->subtotal,
            'discount_total' => (float) $this->discount_total,
            'total' => (float) $this->total,
            'notes' => $this->notes,

            'customer' => [
                'id' => $this->customer_id,
                'code' => $this->customer_code,
                'name' => $this->customer_name,
                'address' => $this->customer_address,
            ],

            'created_by' => [
                'id' => $this->created_by,
                'name' => $this->creator?->name,
            ],

            'details' => TransactionDetailResource::collection($this->whenLoaded('details')),

            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
