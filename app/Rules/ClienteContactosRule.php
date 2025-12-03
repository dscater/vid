<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class ClienteContactosRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail("Debes agregar al menos 1 contacto");
            return;
        }

        foreach ($value as $item) {
            if (trim($item["nombre"]) == '' || trim($item["fono"]) == '' || trim($item["cel"]) == '') {

                $fail("Debes completar todos los campos requeridos(*) del Contacto");
                return;
            }
        }
    }
}
