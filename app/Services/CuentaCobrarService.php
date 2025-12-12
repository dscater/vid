<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\CuentaCobrar;
use App\Models\CuentaCobrarDetalle;
use App\Models\OrdenVenta;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CuentaCobrarService
{

    private $modulo = "CUENTAS POR COBRAR";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $cuenta_cobrars = CuentaCobrar::select("cuenta_cobrars.*")
            ->with(["orden_venta", "cliente", "cuenta_cobrar_detalles"]);

        $cuenta_cobrars = $cuenta_cobrars->get();
        return $cuenta_cobrars;
    }
    /**
     * Lista de cuenta_cobrars paginado con filtros
     *
     * @param integer $length
     * @param integer $page
     * @param string $search
     * @param array $columnsSerachLike
     * @param array $columnsFilter
     * @return LengthAwarePaginator
     */
    public function listadoPaginado(int $length, int $page, string $search, array $columnsSerachLike = [], array $columnsFilter = [], array $columnsBetweenFilter = [], array $orderBy = []): LengthAwarePaginator
    {
        $cuenta_cobrars = CuentaCobrar::select("cuenta_cobrars.*")
            ->with(["orden_venta:id,codigo", "cliente:id,razon_social"]);

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $cuenta_cobrars->where("cuenta_cobrars.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $cuenta_cobrars->whereBetween("cuenta_cobrars.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $cuenta_cobrars->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $cuenta_cobrars->orderBy($value[0], $value[1]);
            }
        }


        $cuenta_cobrars = $cuenta_cobrars->paginate($length, ['*'], 'page', $page);
        return $cuenta_cobrars;
    }

    /**
     * Nueva cuenta_cobrar
     *
     * @param array $datos
     * @return CuentaCobrar
     */
    public function nuevo(OrdenVenta $orden_venta): CuentaCobrar
    {
        $saldo = (float)$orden_venta->total_f - $orden_venta->cancelado;

        $cuenta_cobrar = CuentaCobrar::create([
            "cliente_id" => $orden_venta->cliente_id,
            "orden_venta_id" => $orden_venta->id,
            "total" => $orden_venta->total_f,
            "cancelado" => $orden_venta->cancelado,
            "saldo" => $saldo,
            "fecha" => date("Y-m-d"),
            "hora" => date("H:i:s"),
        ]);

        // registrar accion
        // $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA CUENTA POR COBRAR", $cuenta_cobrar);

        return $cuenta_cobrar;
    }

    /**
     * Crear cuenta_cobrar
     *
     * @param array $datos
     * @return CuentaCobrar
     */
    public function crear(array $datos): CuentaCobrar
    {
        if ($datos["id"]) {
            $cuenta_cobrar = CuentaCobrar::findOrFail($datos["id"]);
            if ($datos["created_at"] && $datos["created_at"] != '') {
                $total_cancelado =  (float)$cuenta_cobrar->cancelado + (float)$datos["cancelado"];
                $cuenta_cobrar->update([
                    "cancelado" => $total_cancelado,
                    "saldo" => $cuenta_cobrar->total - (float)$total_cancelado
                ]);

                // REGISTRAR 
                foreach ($datos["cuenta_cobrar_detalles"] as $item) {
                    if ($item["id"] == 0) {
                        CuentaCobrarDetalle::create([
                            "cuenta_cobrar_id" => $cuenta_cobrar->id,
                            "cancelado" => $item["cancelado"],
                            "fecha" => $item["fecha"],
                            "hora" => $item["hora"],
                        ]);
                        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO EL PAGO DE UNA CUENTA POR COBRAR", $cuenta_cobrar, NULL, ["cuenta_cobrar_detalles"]);
                    }
                }
            }
            return $cuenta_cobrar;
        }

        $orden_venta = OrdenVenta::findOrFail($datos["orden_venta_id"]);
        $saldo = (float)$orden_venta->total_f - $orden_venta->cancelado;

        $cuenta_cobrar = CuentaCobrar::create([
            "cliente_id" => $orden_venta->cliente_id,
            "orden_venta_id" => $orden_venta->id,
            "total" => $orden_venta->total_f,
            "cancelado" => $orden_venta->cancelado,
            "saldo" => $saldo,
            "fecha" => date("Y-m-d"),
            "hora" => date("H:i:s"),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA CUENTA POR COBRAR", $cuenta_cobrar);

        return $cuenta_cobrar;
    }

    public function pago(array $datos, CuentaCobrar $cuenta_cobrar): CuentaCobrar
    {
        CuentaCobrarDetalle::create([
            "cuenta_cobrar_id" => $cuenta_cobrar->id,
            "cancelado" => $datos["cancelado"],
            "fecha" => date("Y-m-d"),
            "hora" => date("H:i:s"),
        ]);

        $total_cancelado =  (float)$cuenta_cobrar->cancelado + (float)$datos["cancelado"];
        $cuenta_cobrar->update([
            "cancelado" => $total_cancelado,
            "saldo" => $cuenta_cobrar->total - (float)$total_cancelado
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO EL PAGO DE UNA CUENTA POR COBRAR", $cuenta_cobrar, NULL, ["cuenta_cobrar_detalles"]);

        return $cuenta_cobrar;
    }

    public function pagoByOrden(array $datos, OrdenVenta $orden_venta): CuentaCobrar
    {
        $cuenta_cobrar = $orden_venta->cuenta_cobrar;
        CuentaCobrarDetalle::create([
            "cuenta_cobrar_id" => $cuenta_cobrar->id,
            "cancelado" => $datos["cancelado"],
            "fecha" => date("Y-m-d"),
            "hora" => date("H:i:s"),
        ]);

        $total_cancelado =  (float)$cuenta_cobrar->cancelado + (float)$datos["cancelado"];
        $cuenta_cobrar->update([
            "cancelado" => $total_cancelado,
            "saldo" => $cuenta_cobrar->total - (float)$total_cancelado
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO EL PAGO DE UNA CUENTA POR COBRAR", $cuenta_cobrar, NULL, ["cuenta_cobrar_detalles"]);

        return $cuenta_cobrar;
    }

    /**
     * Actualizar cuenta_cobrar
     *
     * @param array $datos
     * @param CuentaCobrar $cuenta_cobrar
     * @return CuentaCobrar
     */
    public function actualizar(array $datos, CuentaCobrar $cuenta_cobrar): CuentaCobrar
    {
        $old_cuenta_cobrar = CuentaCobrar::find($cuenta_cobrar->id);
        $cuenta_cobrar->update([
            "razon_social" => mb_strtoupper($datos["razon_social"]),
            "tipo" => mb_strtoupper($datos["tipo"]),
            "nit" => mb_strtoupper($datos["nit"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA CUENTA POR COBRAR", $old_cuenta_cobrar, $cuenta_cobrar);

        return $cuenta_cobrar;
    }

    public function verificarSincronizado(array $datos): CuentaCobrar|null
    {
        if ($datos["id"]) {
            $cuenta_cobrar = CuentaCobrar::findOrFail($datos["id"]);
            $total_cancelado =  (float)$cuenta_cobrar->cancelado + (float)$datos["cancelado"];
            $cuenta_cobrar->update([
                "cancelado" => $total_cancelado,
                "saldo" => $cuenta_cobrar->total - (float)$total_cancelado
            ]);

            // REGISTRAR 
            foreach ($datos["cuenta_cobrar_detalles"] as $item) {
                if ($item["id"] == 0) {
                    CuentaCobrarDetalle::create([
                        "cuenta_cobrar_id" => $cuenta_cobrar->id,
                        "cancelado" => $item["cancelado"],
                        "fecha" => $item["fecha"],
                        "hora" => $item["hora"],
                    ]);
                    $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO EL PAGO DE UNA CUENTA POR COBRAR", $cuenta_cobrar, NULL, ["cuenta_cobrar_detalles"]);
                }
            }
            return $cuenta_cobrar;
        }
        return null;
    }


    /**
     * Eliminar cuenta_cobrar
     *
     * @param CuentaCobrar $cuenta_cobrar
     * @return boolean
     */
    public function eliminar(CuentaCobrar $cuenta_cobrar): bool|Exception
    {
        $old_cuenta_cobrar = clone $cuenta_cobrar;
        $cuenta_cobrar->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA CUENTA POR COBRAR", $old_cuenta_cobrar);

        return true;
    }
}
