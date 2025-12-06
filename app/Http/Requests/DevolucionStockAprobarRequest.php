<?php

namespace App\Http\Requests;

use App\Rules\DevolucionStockDetalleVerificadoRule;
use Illuminate\Foundation\Http\FormRequest;

class DevolucionStockAprobarRequest extends FormRequest
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
            "devolucion_stock_detalles" => ["required", new DevolucionStockDetalleVerificadoRule()],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
