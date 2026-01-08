<?php

namespace App\Http\Requests\DiscountType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiscountTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('discounts.manage');
    }

    public function rules(): array
    {
        return [
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('discount_types', 'code')->ignore($this->route('discount_type'))
            ],
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'type' => ['sometimes', 'required', Rule::in(['percentage', 'fixed'])],
            'value' => ['sometimes', 'required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
