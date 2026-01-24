<?php

namespace App\Http\Requests;

use App\Rules\SolicitudIngresoAprobarCostosRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SolicitudIngresoAprobarCostosRequest extends FormRequest
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
            "cs_f" => "required",
            "tipo_cambio" => "required",
            "gastos" => "required",
            "total" => "required",
            "solicitud_ingreso_detalles" => ["required", new SolicitudIngresoAprobarCostosRule()],
        ];
    }

    public function messages(): array
    {
        return [
            "cs_f" => "Debes completar este campo",
            "tipo_cambio" => "Debes completar este campo",
            "gastos" => "Debes completar este campo",
        ];
    }
}
