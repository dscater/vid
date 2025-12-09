<?php

namespace App\Http\Requests;

use App\Rules\OrdenVentaDetalleRule;
use Illuminate\Foundation\Http\FormRequest;

class OrdenVentaUpdateRequest extends FormRequest
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
            "cliente_id" => "required",
            "fecha" => "required|date",
            "hora" => "required",
            "descripcion" => "nullable",
            "cantidad_total" => "required",
            "forma_pago" => "required",
            "cs_f" => "required",
            "cancelado" => "required|decimal:0,2",
            "cambio" => "nullable|decimal:0,2",
            "total" => "required",
            "total_st" => "required",
            "solicitud_descuento" => "nullable",
            "solicitud_sw" => "nullable",
            "monto_solicitud" => "nullable",
            "descuento" => "nullable",
            "total_f" => "required",
            "orden_venta_detalles" => ["required", new OrdenVentaDetalleRule()],
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
            "orden_venta_detalles.required" => "Debes agregar al menos 1 producto",
        ];
    }
}
