<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionRequest extends FormRequest
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
            "nombre_sistema" => "required",
            "alias" => "required",
            "logo" => "required",
            "envio_email.host" => "required",
            "envio_email.correo" => "required",
            "envio_email.driver" => "required",
            "envio_email.nombre" => "required",
            "envio_email.puerto" => "required",
            "envio_email.password" => "required",
            "envio_email.encriptado" => "required",
        ];
    }

    /**
     * Mensajes validacion
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            "nombre_sistema.required" => "Debes completar este campo",
            "alias.required" => "Debes completar este campo",
            "logo.required" => "Debes completar este campo",
            "fono.required" => "Debes completar este campo",
            "dir.required" => "Debes completar este campo",
            "envio_email.host.required" => "Debes completar este campo",
            "envio_email.correo.required" => "Debes completar este campo",
            "envio_email.driver.required" => "Debes completar este campo",
            "envio_email.nombre.required" => "Debes completar este campo",
            "envio_email.puerto.required" => "Debes completar este campo",
            "envio_email.password.required" => "Debes completar este campo",
            "envio_email.encriptado.required" => "Debes completar este campo",
        ];
    }
}
