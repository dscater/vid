<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TransferenciaDetalleVerificadoRule implements ValidationRule
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
            if (trim($item["verificado"]) == 0) {
                $fail("Debes marcar todas las casillas");
                return;
            }

            if ($item["cantidad"] != $item["cantidad_fisica"]) {
                if (!$item["motivo"])
                    $fail("Debes indicar el motivo de todas las filas con observación");
                return;
            }
        }
    }
}
