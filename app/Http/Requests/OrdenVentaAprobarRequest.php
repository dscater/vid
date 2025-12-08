<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdenVentaAprobarRequest extends FormRequest
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
            "descuento" => "required|decimal:0,2"
        ];
    }

    public function messages()
    {
        return [
            "descuento.required" => "Debes indicar el descuento",
            "descuento.decimal" => "Debes ingresar un valor númerico con hata 2 decimales",
        ];
    }
}
