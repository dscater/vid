<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProformaProductoRule implements ValidationRule
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
            if (trim($item["producto_id"]) == '') {
                $fail("No se seleccionó un producto");
                return;
            }
        }
    }
}
