<?php

namespace App\Http\Requests;

use App\Rules\CertificadoRule;
use App\Rules\DocumentoRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UserStoreRequest extends FormRequest
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
        $validacion = [
            "nombre" => "required|min:2",
            "paterno" => "required|min:1",
            "materno" => "nullable|min:1",
            "ci" => "required|numeric|digits_between:6,10|unique:users,ci",
            "ci_exp" => "required",
            "grupo_san" => "required",
            "sexo" => "required",
            "nacionalidad" => "required",
            "profesion" => "nullable",
            "cel" => "required",
            "fono" => "required",
            "cel_dom" => "required",
            "dir" => "required",
            "latitud" => "required",
            "longitud" => "required",
            "correo" => "required|email|unique:users,correo",
            "tipo" => "required",
            "certificados" => ["nullable", "array", new CertificadoRule()],
            "documentos" => ["nullable", "array", new DocumentoRule()],
        ];

        if ($this->foto) {
            $validacion["foto"] = "image|mimes:png,jpg,jpeg,webp|max:4096";
        }

        if ($this->carnet) {
            $validacion["carnet"] = "image|mimes:png,jpg,jpeg,webp|max:4096";
        }

        if ($this->tipo == 'USUARIO') {
            $validacion["role_id"] = "required";
            $validacion["acceso"] = "required";
        }

        return $validacion;
    }

    /**
     * Mensages validacion
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            "nombre.required" => "Este campo es obligatorio",
            "nombre.min" => "Debes ingresar al menos :min caracteres",
            "paterno.required" => "Este campo es obligatorio",
            "paterno.min" => "Debes ingresar al menos :min caracteres",
            "materno.min" => "Debes ingresar al menos :min caracteres",
            "ci.required" => "Este campo es obligatorio",
            "ci.numeric" => "Debes ingresar un valor númerico",
            "ci.digits_between" => "Debes ingresar un valor entre 6 y 10 digitos",
            "ci.unique" => "Este número de C.I. ya fue registrado",
            "ci_exp.required" => "Este campo es obligatorio",
            "grupo_san.required" => "Este campo es obligatorio",
            "sexo.required" => "Este campo es obligatorio",
            "nacionalidad.required" => "Este campo es obligatorio",
            "profesion.required" => "Este campo es obligatorio",
            "cel.required" => "Este campo es obligatorio",
            "fono.required" => "Este campo es obligatorio",
            "fono.min" => "Debes ingresar al menos :min caracteres",
            "cel_dom.required" => "Este campo es obligatorio",
            "dir.required" => "Este campo es obligatorio",
            "dir.min" => "Debes ingresar al menos :min caracteres",
            "latitud.required" => "Este campo es obligatorio",
            "longitud.required" => "Este campo es obligatorio",
            "correo.required" => "Este campo es obligatorio",
            "correo.email" => "Debes ingresar un correo valido",
            "correo.unique" => "Este correo no esta disponible",
            "role_id.required" => "Este campo es obligatorio",
            "foto.image" => "Debes cargar una imagen",
            "foto.mimes" => "Solo puedes enviar formatos png,jpg,jpeg,webp",
            "foto.max" => "No puedes cargar una imagen con mas de 4096KB",
            "acceso.required" => "Este campo es obligatorio",
        ];
    }
}
