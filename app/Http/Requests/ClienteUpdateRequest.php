<?php

namespace App\Http\Requests;

use App\Rules\ClienteContactosRule;
use Illuminate\Foundation\Http\FormRequest;

class ClienteUpdateRequest extends FormRequest
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
            "razon_social" => "required",
            "tipo" => "required",
            "nit" => "required",
            "nombre_punto" => "required",
            "nombre_prop" => "required",
            "ci_prop" => "required",
            "correo" => "nullable|email",
            "cel" => "required",
            "fono" => "required",
            "dir" => "required",
            "latitud" => "required",
            "longitud" => "required",
            "ciudad" => "required",
            "contactos" => ["required", "array", "min:1", new ClienteContactosRule()],
            "estado" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "razon_social.required" => "Debes completar este campo",
            "tipo.required" => "Debes completar este campo",
            "nit.required" => "Debes completar este campo",
            "nombre_punto.required" => "Debes completar este campo",
            "nombre_prop.required" => "Debes completar este campo",
            "ci_prop.required" => "Debes completar este campo",
            "correo.required" => "Debes completar este campo",
            "cel.required" => "Debes completar este campo",
            "fono.required" => "Debes completar este campo",
            "dir.required" => "Debes completar este campo",
            "latitud.required" => "Debes completar este campo",
            "longitud.required" => "Debes completar este campo",
            "ciudad.required" => "Debes completar este campo",
            "contactos.required" => "Debes completar este campo",
            "contactos.min" => "Debes agregar al menos :min contacto",
            "estado.required" => "Debes completar este campo",
        ];
    }
}
