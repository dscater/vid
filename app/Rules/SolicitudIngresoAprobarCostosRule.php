<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SolicitudIngresoAprobarCostosRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail("No se encontrarón productos");
            return;
        }

        foreach ($value as $item) {
            if ($item["costo"] == '') {
                $fail("Debes indicar el costo");
                return;
            }
        }
    }
}
