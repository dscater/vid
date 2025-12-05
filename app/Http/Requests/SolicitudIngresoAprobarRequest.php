<?php

namespace App\Http\Requests;

use App\Rules\SolicitudDetalleVerificadoRule;
use Illuminate\Foundation\Http\FormRequest;

class SolicitudIngresoAprobarRequest extends FormRequest
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
            "verificado" => "required",
            "solicitud_ingreso_detalles" => ["required", new SolicitudDetalleVerificadoRule()],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
