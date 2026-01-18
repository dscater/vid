<?php

namespace App\Services;

use App\Jobs\RecalcularRankingClientes;
use App\Models\Cliente;
use App\Models\CuentaCobrar;
use App\Models\OrdenVenta;
use App\Services\HistorialAccionService;
use App\Models\ParametroCliente;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ParametroClienteService
{

    private $modulo = "PARAMETRO CLIENTES";

    public function __construct(private HistorialAccionService $historialAccionService) {}


    public function getParametro()
    {
        $parametro_cliente = ParametroCliente::get()->first();
        if (!$parametro_cliente) {
            $parametro_cliente = ParametroCliente::create([
                "valor1" => 0.089,
                "valor2" => 0.0462,
            ]);
        }

        return $parametro_cliente;
    }

    /**
     * Actualizar parametro_cliente
     *
     * @param array $datos
     * @param ParametroCliente $parametro_cliente
     */
    public function actualizar(array $datos): ParametroCliente
    {
        $parametro_cliente = ParametroCliente::get()->first();
        if (!$parametro_cliente) {
            $parametro_cliente = ParametroCliente::create([
                "valor1" => 0.089,
                "valor2" => 0.0462,
            ]);
        }

        $old_parametro_cliente = clone $parametro_cliente;

        $parametro_cliente->update([
            "valor1" => $datos["valor1"],
            "valor2" => $datos["valor2"],
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ LOS PARAMETROS PARA EL CALCULO DE RANK DE CLIENTES", $old_parametro_cliente, $parametro_cliente);

        return $parametro_cliente;
    }

    public function verificarRankCliente($cliente_id)
    {
        $cliente = Cliente::findOrFail($cliente_id);
        $parametro_cliente = $this->getParametro();

        $hoy = Carbon::now();
        $fecha365 = $hoy->copy()->subDays(365);
        $fecha65  = $hoy->copy()->subDays(65);

        $ganancias365 = DB::table('orden_venta_detalles as d')
            ->join('productos as p', 'p.id', '=', 'd.producto_id')
            ->join('orden_ventas as o', 'o.id', '=', 'd.orden_venta_id')
            ->where('o.fecha', '>=', $fecha365)
            ->where('o.cliente_id', $cliente->id)
            ->selectRaw('SUM(d.subtotal_f - (d.cantidad * p.ppp)) as ganancia')
            ->value('ganancia');
        $importe365 = OrdenVenta::where("cliente_id", $cliente->id)
            ->where("fecha", ">=", $fecha365)->sum("total_f");
        $importe65 = OrdenVenta::where("cliente_id", $cliente->id)
            ->where("fecha", ">=", $fecha65)->sum("total_f");
        $deudas = CuentaCobrar::where("cliente_id", $cliente->id)->sum("saldo");


        // Log::debug("IMPORTE 365: " . $importe365);
        // Log::debug("DEUDA: " . $deudas);
        // Log::debug("GANANCIA 365: " . $ganancias365);
        // Log::debug("IMPORTE 65: " . $importe65);
        // 
        $valor1 = $parametro_cliente->valor1;
        $valor2 = $parametro_cliente->valor2;
        $factor = 1; // ordenar tomando en cuenta esto
        $posicion = OrdenVenta::select('cliente_id')
            ->where('fecha', '>=', $fecha365)
            ->groupBy('cliente_id')
            ->havingRaw('SUM(total_f) > ?', [$importe365])
            ->count() + 1;
        if ($posicion <= 15) {
            $factor = 1.50;
        } elseif ($posicion <= 65) {
            $factor = 1.25;
        } else {
            $factor = 1.00;
        }

        $resultado = (((float)$valor1 * ($importe365 + $deudas)) + ($factor * $ganancias365) + ((float)$valor2) * $importe65) / 3;
        $resultado = round($resultado, 2);
        // Log::debug("RESULTADO: " . $resultado);

        $cliente->update([
            "score" => $resultado,
            "total_credito" => $resultado,
            "factor" => $factor
        ]);

        return $cliente;
    }


    public function asignarRank()
    {
        DB::transaction(function () {

            Cliente::where('estado', 1)
                ->update([
                    'ranking' => null,
                    'categoria' => null,
                ]);

            $clientes = Cliente::where('estado', 1)
                ->where('score', '>', 0)
                ->orderByDesc('score')
                ->get()
                ->values();

            foreach ($clientes as $index => $cliente) {

                $posicion = $index + 1;

                $categoria = match (true) {
                    $posicion <= 15 => 'A',
                    $posicion <= 65 => 'B',
                    default => 'C',
                };

                $cliente->update([
                    'ranking' => $posicion,
                    'categoria' => $categoria,
                ]);
            }
        });
    }
}
