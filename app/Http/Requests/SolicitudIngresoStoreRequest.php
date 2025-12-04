<?php

namespace App\Http\Requests;

use App\Rules\SolicitudDetalleRule;
use Illuminate\Foundation\Http\FormRequest;

class SolicitudIngresoStoreRequest extends FormRequest
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
            "proveedor_id" => "required",
            "fecha_ingreso" => "required",
            "hora_ingreso" => "required",
            "cs_f" => "required",
            "tipo_cambio" => "required",
            "gastos" => "required",
            "observaciones" => "nullable",
            "descripcion" => "nullable",
            "cantidad_total" => "required",
            "total" => "required",
            "solicitud_ingreso_detalles" => ["required", new SolicitudDetalleRule()],
            "eliminados_detalles" => "nullable",
        ];
    }

    public function messages(): array
    {
        return [
            "proveedor_id" => "Debes completar este campo",
            "fecha_ingreso" => "Debes completar este campo",
            "hora_ingreso" => "Debes completar este campo",
            "fecha_sis" => "Debes completar este campo",
            "tipo_cambio" => "Debes completar este campo",
            "gastos" => "Debes completar este campo",
            "observaciones" => "Debes completar este campo",
            "descripcion" => "Debes completar este campo",
            "cantidad_total" => "Debes completar este campo",
            "total" => "Debes completar este campo",
            "solicitud_ingreso_detalles" => "Debes agregar al menos 1 producto",
        ];
    }
}
