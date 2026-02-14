<?php
// app/Http/Requests/CloseCashRegisterRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseCashRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('close', $this->route('session'));
    }

    public function rules(): array
    {
        return [
            'final_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
