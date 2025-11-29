<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormularioRegistroStoreRequest extends FormRequest
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
            "ci" => "required",
            "codigo" => "required"
        ];
    }

    public function messages(): array
    {
        return [
            "ci.required" => "Debes ingresar tu Nro. de C.I.",
            "ci.codigo" => "Debes ingresar el código que se envío a tu correo",
        ];
    }
}
