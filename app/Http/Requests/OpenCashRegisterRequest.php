<?php
// app/Http/Requests/OpenCashRegisterRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenCashRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'initial_amount' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'initial_amount.required' => 'El monto inicial es obligatorio',
            'initial_amount.numeric' => 'El monto debe ser un número',
            'initial_amount.min' => 'El monto no puede ser negativo',
        ];
    }
}


