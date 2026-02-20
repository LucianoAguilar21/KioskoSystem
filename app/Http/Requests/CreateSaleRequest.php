<?php
// app/Http/Requests/CreateSaleRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('payment_amounts') && is_string($this->payment_amounts)) {
            $this->merge([
                'payment_amounts' => json_decode($this->payment_amounts, true)
            ]);
        }

        if ($this->has('items') && is_string($this->items)) {
            $this->merge([
                'items' => json_decode($this->items, true)
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,card,transfer,mixed'],
            'payment_amounts' => ['required', 'array'],
            'payment_amounts.cash' => ['nullable', 'numeric', 'min:0'],
            'payment_amounts.card' => ['nullable', 'numeric', 'min:0'],
            'payment_amounts.transfer' => ['nullable', 'numeric', 'min:0'],
            'change_amount' => ['nullable', 'numeric', 'min:0'],
            'total_paid' => ['nullable', 'numeric', 'min:0'],
            'auto_print' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Debe agregar al menos un producto',
            'items.array' => 'Los productos deben ser un array',
            'items.min' => 'Debe agregar al menos un producto',
            'payment_method.required' => 'Debe seleccionar un método de pago',
            'payment_method.in' => 'Método de pago inválido',
        ];
    }
}
