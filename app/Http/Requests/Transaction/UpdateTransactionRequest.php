<?php
// app/Http/Requests/Transaction/UpdateTransactionRequest.php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('transactions.update');
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes', 'required', 'exists:customers,id'],
            'invoice_date' => ['sometimes', 'nullable', 'date'],
            'notes' => ['nullable', 'string'],

            'items' => ['sometimes', 'required', 'array', 'min:1'],
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
}
