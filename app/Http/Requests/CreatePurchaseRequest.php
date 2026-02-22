<?php
// app/Http/Requests/CreatePurchaseRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Purchase::class);
    }

    /**
     * Prepara los datos antes de la validación
     */
    protected function prepareForValidation()
    {
        // Si items viene como string JSON, decodificarlo
        if ($this->has('items') && is_string($this->items)) {
            $this->merge([
                'items' => json_decode($this->items, true)
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'invoice_number' => ['nullable', 'string', 'max:100'],
            'purchase_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Debe seleccionar un proveedor',
            'supplier_id.exists' => 'El proveedor seleccionado no existe',
            'purchase_date.required' => 'La fecha de compra es obligatoria',
            'items.required' => 'Debe agregar al menos un producto',
            'items.array' => 'Los productos deben ser un array',
            'items.min' => 'Debe agregar al menos un producto',
            'items.*.product_id.required' => 'Debe seleccionar un producto',
            'items.*.product_id.exists' => 'El producto seleccionado no existe',
            'items.*.quantity.required' => 'La cantidad es obligatoria',
            'items.*.quantity.min' => 'La cantidad debe ser al menos 1',
            'items.*.unit_cost.required' => 'El costo unitario es obligatorio',
            'items.*.unit_cost.min' => 'El costo unitario debe ser mayor a 0',
        ];
    }
}
