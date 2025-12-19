<?php

namespace App\Http\Requests;

use App\Rules\ProformaDetalleRule;
use App\Rules\ProformaProductoRule;
use Illuminate\Foundation\Http\FormRequest;

class ProformaUpdateRequest extends FormRequest
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
            "sucursal_ids" => "required|array",
            "fecha" => "required|date",
            "hora" => "required",
            "total" => "nullable",
            "proforma_productos" => ["required", new ProformaProductoRule()],
            "eliminados_productos" => "nullable",
            "proforma_detalles" => ["required", new ProformaDetalleRule()],
            "eliminados_detalles" => "nullable",
        ];
    }

    public function messages(): array
    {
        return [
            "sucursal_id.required" => "Debes completar este campo",
            "cliente_id.required" => "Debes completar este campo",
            "fecha.required" => "Debes completar este campo",
            "hora.required" => "Debes completar este campo",
            "forma_pago.required" => "Debes completar este campo",
            "cs_f.required" => "Debes completar este campo",
            "cancelado.required" => "Debes completar este campo",
            "cancelado.decimal" => "Debes ingresar un valor númerico con hasta 2 decimales",
            "cambio.decimal" => "Debes ingresar un valor númerico con hasta 2 decimales",
            "observaciones.required" => "Debes completar este campo",
            "cantidad_total.required" => "Debes completar este campo",
            "total.required" => "No se pudo obtener el TOTAL de Orden de Venta",
            "total_f.required" => "No se pudo obtener el TOTAL de Orden de Venta",
            "proforma_detalles.required" => "Debes agregar al menos 1 producto",
        ];
    }
}
