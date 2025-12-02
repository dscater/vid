<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SucursalStoreRequest extends FormRequest
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
            "nombre" => "required",
            "direccion" => "required",
            "fono" => "required",
            "correo" => "nullable|email",
            "user_id" => "required",
            "estado" => "boolean|in:0,1",
        ];
    }

    public function messages(): array
    {
        return [
            "nombre.required" => "Debes completar este campo",
            "direccion.required" => "Debes completar este campo",
            "fono.required" => "Debes completar este campo",
            "correo.required" => "Debes completar este campo",
            "user_id.required" => "Debes completar este campo",
        ];
    }
}
