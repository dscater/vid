<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SucursalProductoUpdateRequest extends FormRequest
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
            "cantidad_ideal" => "required",
            "cantidad_minima" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "cantidad_ideal.required" => "Debes completar este campo",
            "cantidad_minima.required" => "Debes completar este campo",
        ];
    }
}
