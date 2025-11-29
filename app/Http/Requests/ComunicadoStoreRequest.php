<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComunicadoStoreRequest extends FormRequest
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
            "unidad" => "required",
            "descripcion" => "required",
            "imagen" => "required",
        ];
    }

    public function messages()
    {
        return [
            "unidad.required" => "Debes completar este campo",
            "descripcion.required" => "Debes completar este campo",
            "imagen.required" => "Debes completar este campo",
        ];
    }
}
