<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('customers.create');
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9]+$/', 'unique:customers,code'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'sub_district' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.regex' => 'Customer code must be alphanumeric only (no special characters)',
            'code.unique' => 'Customer code already exists',
        ];
    }
}
