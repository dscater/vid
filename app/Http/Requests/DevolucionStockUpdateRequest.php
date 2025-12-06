<?php

namespace App\Http\Requests;

use App\Rules\DevolucionStockDetalleRule;
use Illuminate\Foundation\Http\FormRequest;

class DevolucionStockUpdateRequest extends FormRequest
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
            "observaciones" => "nullable",
            "cantidad_total" => "required",
            "fecha" => "required|date",
            "hora" => "required",
            "total" => "required",
            "devolucion_stock_detalles" => ["required", new DevolucionStockDetalleRule()],
            "eliminados_detalles" => "nullable",
        ];
    }

    public function messages(): array
    {
        return [
            "sucursal_id" => "Debes completar este campo",
            "fecha" => "Debes completar este campo",
            "hora" => "Debes completar este campo",
            "observaciones" => "Debes completar este campo",
            "cantidad_total" => "Debes completar este campo",
            "total" => "Debes completar este campo",
            "devolucion_stock_detalles" => "Debes agregar al menos 1 producto",
        ];
    }
}
