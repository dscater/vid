<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GastoStoreRequest extends FormRequest
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
            "descripcion" => "required",
            "monto" => "required",
            "fecha" => "required",
            "hora" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "descripcion.required" => "Debes completar este campo",
            "monto.required" => "Debes completar este campo",
            "fecha.required" => "Debes completar este campo",
            "hora.required" => "Debes completar este campo",
        ];
    }
}
