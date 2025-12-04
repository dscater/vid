<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SolicitudDetalleRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail("Debes agregar al menos 1 producto");
            return;
        }

        foreach ($value as $item) {
            if (trim($item["cantidad"]) == '') {

                $fail("Debes ingresar todas las cantidades solicitadas");
                return;
            }
        }
    }
}
