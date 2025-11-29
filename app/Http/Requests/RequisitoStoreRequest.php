<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RequisitoStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Auth::check() && Auth::user()->tipo == 'POSTULANTE') {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "file1" => "required|file|mimes:pdf",
            "file2" => "required|file|mimes:pdf",
            "file3" => "required|file|mimes:pdf",
            "file4" => "required|file|mimes:pdf",
            "file6" => "required|file|mimes:pdf",
            "file7" => "required|file|mimes:pdf",
            "file8" => "required|file|mimes:pdf",
            "file9" => "required|file|mimes:pdf",
            "file10" => "required|file|mimes:pdf",
            "file11" => "required|file|mimes:pdf",
            "file12" => "required|file|mimes:pdf",
            "file13" => "required|file|mimes:pdf",
            "terminos_condiciones" => "accepted",
        ];

        // Solo si es femenino, requerir file5
        if (auth()->user() && auth()->user()->inscripcion->postulante->genero === 'F') {
            $rules['file5'] = 'required|file|mimes:pdf';
        } else {
            $rules['file5'] = 'nullable|file|mimes:pdf';
        }
        if (auth()->user() && auth()->user()->inscripcion->postulante->edad_lim < 18) {
            $rules['file14'] = 'required|file|mimes:pdf';
        } else {
            $rules['file14'] = 'nullable|file|mimes:pdf';
        }

        return $rules;
    }
    public function messages(): array
    {
        return [
            "file1.required" => "Debes cargar un archivo",
            "file2.required" => "Debes cargar un archivo",
            "file3.required" => "Debes cargar un archivo",
            "file4.required" => "Debes cargar un archivo",
            "file5.required" => "Debes cargar un archivo",
            "file6.required" => "Debes cargar un archivo",
            "file7.required" => "Debes cargar un archivo",
            "file8.required" => "Debes cargar un archivo",
            "file9.required" => "Debes cargar un archivo",
            "file10.required" => "Debes cargar un archivo",
            "file11.required" => "Debes cargar un archivo",
            "file12.required" => "Debes cargar un archivo",
            "file13.required" => "Debes cargar un archivo",
            "file14.required" => "Debes cargar un archivo",
            "terminos_condiciones.accepted" => "Debes aceptar los términos y condiciones",
        ];
    }
}
