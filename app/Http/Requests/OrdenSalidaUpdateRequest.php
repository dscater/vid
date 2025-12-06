<?php

namespace App\Http\Requests;

use App\Rules\OrdenSalidaDetalleRule;
use Illuminate\Foundation\Http\FormRequest;

class OrdenSalidaUpdateRequest extends FormRequest
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
            "sucursal_id" => "required",
            "user_sol" => "required",
            "user_ap" => "required",
            "observaciones" => "nullable",
            "descripcion" => "nullable",
            "cantidad_total" => "required",
            "total" => "required",
            "orden_salida_detalles" => ["required", new OrdenSalidaDetalleRule()],
            "eliminados_detalles" => "nullable",
        ];
    }

    public function messages(): array
    {
        return [
            "sucursal_id" => "Debes completar este campo",
            "user_sol" => "Debes completar este campo",
            "user_ap" => "Debes completar este campo",
            "observaciones" => "Debes completar este campo",
            "cantidad_total" => "Debes completar este campo",
            "total" => "Debes completar este campo",
            "orden_salida_detalles" => "Debes agregar al menos 1 producto",
        ];
    }
}
