<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParametroSucursalUpdateStore extends FormRequest
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
            "valor1" => "required",
            "valor2" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "valor1.required" => "Debes completar este campo",
            "valor2.required" => "Debes completar este campo",
        ];
    }
}
