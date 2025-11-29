<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostulanteUpdateRequest extends FormRequest
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

        // Log::debug(request());
        $validacion = [
            "nombre" => "required",
            "paterno" => "required",
            "materno" => "nullable",
            "ci_exp" => "required",
            "complemento" => "nullable|alpha_num",
            "genero" => "required",
            "fecha_nac" => ["required", "date"],
            "cel" => "required",
            // "correo" => "required|email",
            "correo" => "required|email|unique:inscripcions,correo," . $this->id, //DESCOMENTAR
            "nro_cuenta" => "required",
            "lugar_preins" => "required",
            "observacion" => "nullable|string",
            "existente" => "boolean",
            "id" => "numeric",
        ];

        $validacion["ci"] = [
            "required",
            "numeric",
            "digits_between:7,10",
            Rule::unique('postulantes', 'ci')
                ->where(function ($query) {
                    $complemento = request()->input('complemento');
                    if (is_null($complemento)) {
                        $query->whereIn('complemento', [NULL, ""]);
                    } else {
                        $query->where('complemento', $complemento);
                    }
                })->ignore($this->postulante_id),
        ];

        return $validacion;
    }

    public function messages(): array
    {
        return [
            "nombre.required" => "Este campo es obligatorio",
            "paterno.required" => "Este campo es obligatorio",
            "materno.required" => "Este campo es obligatorio",
            "ci.required" => "Este campo es obligatorio",
            "ci_exp.required" => "Este campo es obligatorio",
            "complemento.required" => "Este campo es obligatorio",
            "genero.required" => "Este campo es obligatorio",
            "unidad.required" => "Este campo es obligatorio",
            "fecha_nac.required" => "Este campo es obligatorio",
            "fecha_nac.date" => "Debes ingresar una fecha valida",
            "cel.required" => "Este campo es obligatorio",
            "correo.required" => "Este campo es obligatorio",
            "correo.email" => "Debes ingresar un correo valido",
            "nro_cuenta.required" => "Este campo es obligatorio",
            "lugar_preins.required" => "Este campo es obligatorio",
            "observacion.string" => "Debes ingresar un texto valido",
        ];
    }
}
