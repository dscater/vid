<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequisitoUpdateRequest extends FormRequest
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
        $rules = [
            "file1" => "nullable|file|mimes:pdf",
            "file2" => "nullable|file|mimes:pdf",
            "file3" => "nullable|file|mimes:pdf",
            "file4" => "nullable|file|mimes:pdf",
            "file5" => "nullable|file|mimes:pdf",
            "file6" => "nullable|file|mimes:pdf",
            "file7" => "nullable|file|mimes:pdf",
            "file8" => "nullable|file|mimes:pdf",
            "file9" => "nullable|file|mimes:pdf",
            "file10" => "nullable|file|mimes:pdf",
            "file11" => "nullable|file|mimes:pdf",
            "file12" => "nullable|file|mimes:pdf",
            "file13" => "nullable|file|mimes:pdf",
            "file14" => "nullable|file|mimes:pdf",
        ];
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
        ];
    }
}
