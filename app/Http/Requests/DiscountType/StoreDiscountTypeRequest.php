<?php

namespace App\Http\Requests\DiscountType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiscountTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('discounts.manage');
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:discount_types,code'],
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
