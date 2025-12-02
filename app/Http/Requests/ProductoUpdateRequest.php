<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoUpdateRequest extends FormRequest
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
            "codigo" => "required|unique:productos,codigo," . $this->producto->id,
            "nombre" => "required",
            "unidades_caja" => "required",
            "descripcion" => "nullable",
            "categoria_id" => "required",
            "marca_id" => "required",
            "precio" => "required",
            // "ppp" => "required",
            "unidad_medida_id" => "required",
            "estado" => "required",
            "imagen" => "nullable",
        ];
    }

    public function messages(): array
    {
        return [
            "codigo.required" => "Debes completar este campo",
            "codigo.unique" => "Este código ya esta en uso",
            "nombre.required" => "Debes completar este campo",
            "unidades_caja.required" => "Debes completar este campo",
            "descripcion.required" => "Debes completar este campo",
            "categoria_id.required" => "Debes completar este campo",
            "marca_id.required" => "Debes completar este campo",
            "precio.required" => "Debes completar este campo",
            // "precio_ppp.required" => "Debes completar este campo",
            // "ppp.required" => "Debes completar este campo",
            "unidad_medida_id.required" => "Debes completar este campo",
            "estado.required" => "Debes completar este campo",
            "imagen.required" => "Debes completar este campo",
        ];
    }
}
