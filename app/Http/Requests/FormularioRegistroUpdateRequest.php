<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormularioRegistroUpdateRequest extends FormRequest
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
            "password" => "required|numeric|digits:6",
            "foto" => "image|mimes:jpg,jpeg,png,webp|dimensions:width=320,height=320"
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Debes ingresar tu contraseña.',
            'password.numeric' => 'La contraseña debe ser un valor númerico.',
            'password.digits' => 'La contraseña debe tener exactamente 6 dígitos numéricos.',
            "foto.image" => "Debes cargar una imagen",
            "foto.mimes" => "Debes cargar una imagen jpg,jpeg,png,web",
            'foto.dimensions' => 'La imagen debe tener un tamaño 320x320 píxeles.',
        ];
    }
}
