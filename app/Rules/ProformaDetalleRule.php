<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProformaDetalleRule implements ValidationRule
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
            if (trim($item["cliente_id"]) == '') {
                $fail("No se ingresó cliente");
                return;
            }
            if (trim($item["total"]) == '') {
                $fail("No se detecto el total");
                return;
            }
        }
    }
}
