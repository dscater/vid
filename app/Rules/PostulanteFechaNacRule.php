<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PostulanteFechaNacRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail("Debes ingresar una fecha de nacimiento");
        }

        // VERIFICAR QUE LA PERSONA NO SEA MENOR DE 17 AÑOS HASTA ESA FECHA
        // O VERIFICAR QUE NO CUMPLA 23 AÑOS HASTA ESA FECHA
        try {
            $fechaNacimiento = Carbon::parse($value);
        } catch (\Exception $e) {
            $fail("La fecha de nacimiento no tiene un formato válido.");
            return;
        }

        $fechaLimite = Carbon::create(2025, 12, 31);
        $fecha17 = $fechaNacimiento->copy()->addYears(17);
        $fecha23 = $fechaNacimiento->copy()->addYears(23);

        if ($fecha17->isAfter($fechaLimite)) {
            $fail("Debes tener al menos 17 años hasta el 31/12/2025.");
        } elseif (!$fecha23->isAfter($fechaLimite)) {
            $fail("No debes haber cumplido 23 años hasta el 31/12/2025.");
        }
    }
}
