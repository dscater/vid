<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuentaCobrarStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "cancelado" => "required|decimal:0,2"
        ];
    }

    public function messages()
    {
        return [
            "cancelado.required" => "Debes ingresar el monto",
            "cancelado.decimal" => "Debes ingresar un valor númerico"
        ];
    }
}
