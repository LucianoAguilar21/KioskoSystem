<?php
// app/Http/Requests/UpdateProductRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->product);
    }

    public function rules(): array
    {
        return [
            'code' => [
                'nullable', 
                'string', 
                'max:50', 
                Rule::unique('products', 'code')->ignore($this->product->id)
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0', 'gte:cost_price'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio',
            'sale_price.gte' => 'El precio de venta debe ser mayor o igual al precio de costo',
            'expires_at.after' => 'La fecha de vencimiento debe ser posterior a hoy',
        ];
    }
}