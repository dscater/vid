<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\ParametroSucursal;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParametroSucursalService
{

    private $modulo = "PARAMETRO SUCURSALES";

    public function __construct(private HistorialAccionService $historialAccionService) {}


    public function getParametro()
    {
        $parametro_sucursal = ParametroSucursal::get()->first();
        if (!$parametro_sucursal) {
            $parametro_sucursal = ParametroSucursal::create([
                "valor1" => "08:00",
                "valor2" => "20:00",
            ]);
        }

        return $parametro_sucursal;
    }

    /**
     * Actualizar parametro_sucursal
     *
     * @param array $datos
     * @param ParametroSucursal $parametro_sucursal
     */
    public function actualizar(array $datos): ParametroSucursal
    {
        $parametro_sucursal = ParametroSucursal::get()->first();
        if (!$parametro_sucursal) {
            $parametro_sucursal = ParametroSucursal::create([
                "valor1" => "08:00",
                "valor2" => "20:00",
            ]);
        }

        $old_parametro_sucursal = clone $parametro_sucursal;

        $parametro_sucursal->update([
            "valor1" => $datos["valor1"],
            "valor2" => $datos["valor2"],
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ LA HORA DE GUARDADO DE STOCK DE SUCURSALES", $old_parametro_sucursal, $parametro_sucursal);

        return $parametro_sucursal;
    }
}
