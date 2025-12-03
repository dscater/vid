<?php

namespace App\Http\Requests;

use App\Rules\ProveedorContactosRule;
use Illuminate\Foundation\Http\FormRequest;

class ProveedorUpdateRequest extends FormRequest
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
            "nombre_com" => "nullable",
            "nit" => "required",
            "moneda" => "required",
            "fono_emp" => "required",
            "correo" => "required",
            "dir" => "required",
            "ciudad" => "required",
            "tipo" => "required",
            "estado" => "required",
            "observaciones" => "nullable",
            "categorias" => "required|array|min:1",
            "marcas" => "required|array|min:1",
            "contactos" => ["required", "array", "min:1", new ProveedorContactosRule()],
        ];
    }

    public function messages(): array
    {
        return [
            "razon_social.required" => "Debes completar este campo",
            "nombre_com.required" => "Debes completar este campo",
            "nit.required" => "Debes completar este campo",
            "moneda.required" => "Debes completar este campo",
            "fono_emp.required" => "Debes completar este campo",
            "correo.required" => "Debes completar este campo",
            "dir.required" => "Debes completar este campo",
            "ciudad.required" => "Debes completar este campo",
            "tipo.required" => "Debes completar este campo",
            "estado.required" => "Debes completar este campo",
            "observaciones.required" => "Debes completar este campo",
            "categorias.required" => "Debes completar este campo",
            "categorias.min" => "Debes agregar al menos :min contacto",
            "marcas.required" => "Debes completar este campo",
            "marcas.min" => "Debes agregar al menos :min contacto",
            "contactos.required" => "Debes completar este campo",
            "contactos.min" => "Debes agregar al menos :min contacto",
        ];
    }
}
