<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('transactions.create');
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'invoice_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],

            'items.*.discounts' => ['nullable', 'array'],
            'items.*.discounts.*.discount_type_id' => ['nullable', 'exists:discount_types,id'],
            'items.*.discounts.*.name' => ['required', 'string', 'max:100'],
            'items.*.discounts.*.type' => ['required', 'in:percentage,fixed'],
            'items.*.discounts.*.value' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Transaction must have at least one item',
            'items.*.product_id.required' => 'Product is required for each item',
            'items.*.product_id.exists' => 'Invalid product selected',
            'items.*.qty.required' => 'Quantity is required for each item',
            'items.*.qty.min' => 'Quantity must be at least 1',
        ];
    }
}
