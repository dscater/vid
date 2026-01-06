<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AjusteStoreRequest extends FormRequest
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
            "sucursal_origen" => "required",
            "cantidad" => "required",
        ];
    }

    public function messages()
    {
        return [
            "sucursal_origen.required" => "Debes completar este campo",
            "cantidad.required" => "Debes completar este campo",
        ];
    }
}
