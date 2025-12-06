<?php

namespace App\Services;

use App\Models\Producto;
use App\Services\HistorialAccionService;
use App\Models\OrdenSalida;
use App\Models\OrdenSalidaDetalle;
use App\Models\Sucursal;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrdenSalidaService
{
    private $modulo = "ORDEN DE SALIDA";
    public function __construct(
        private HistorialAccionService $historialAccionService,
        private KardexProductoService $kardex_producto_service,
        private SucursalProductoService $sucursal_producto_service
    ) {}

    public function listado(): Collection
    {
        $orden_salidas = OrdenSalida::select("orden_salidas.*")->where("usuarios", 1)->get();
        return $orden_salidas;
    }
    /**
     * Lista de orden_salidas paginado con filtros
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
        $orden_salidas = OrdenSalida::select("orden_salidas.*")
            ->with(["sucursal:id,nombre", "user:id,nombre,paterno,materno", "user_solicitante:id,nombre,paterno,materno", "user_aprobador:id,nombre,paterno,materno"]);
        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $orden_salidas->where("orden_salidas.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $orden_salidas->whereBetween("orden_salidas.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $orden_salidas->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $orden_salidas->orderBy($value[0], $value[1]);
            }
        }


        $orden_salidas = $orden_salidas->paginate($length, ['*'], 'page', $page);
        return $orden_salidas;
    }

    /**
     * Crear orden_salida
     *
     * @param array $datos
     * @return OrdenSalida
     */
    public function crear(array $datos): OrdenSalida
    {
        $nuevo_codigo = $this->generarCodigo();
        $orden_salida = OrdenSalida::create([
            "nro" => $nuevo_codigo[0],
            "codigo" => $nuevo_codigo[1],
            "sucursal_id" => $datos["sucursal_id"],
            "user_sol" => $datos["user_sol"],
            "user_ap" => $datos["user_ap"],
            "fecha" => date("Y-m-d"),
            "hora" => date("H:i"),
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "cantidad_total" => $datos["cantidad_total"],
            "total" => $datos["total"],
            "estado" => "PENDIENTE",
            "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["orden_salida_detalles"] as $item) {
            $orden_salida->orden_salida_detalles()->create([
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "cantidad_fisica" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA ORDEN DE SALIDA", $orden_salida);

        return $orden_salida;
    }

    public function generarCodigo()
    {
        $ultimo = OrdenSalida::orderBy("nro")->get()->last();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro + 1;
        }
        $codigo = "SAL." . $nro;
        return [$nro, $codigo];
    }

    /**
     * Actualizar orden_salida
     *
     * @param array $datos
     * @param OrdenSalida $orden_salida
     * @return OrdenSalida
     */
    public function actualizar(array $datos, OrdenSalida $orden_salida): OrdenSalida
    {
        $old_orden_salida = clone $orden_salida;
        $old_orden_salida->loadMissing(["orden_salida_detalles"]);
        $orden_salida->update([
            "sucursal_id" => $datos["sucursal_id"],
            "user_sol" => $datos["user_sol"],
            "user_ap" => $datos["user_ap"],
            "fecha" => date("Y-m-d"),
            "hora" => date("H:i"),
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "cantidad_total" => $datos["cantidad_total"],
            "total" => $datos["total"],
            "estado" => "PENDIENTE",
            // "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["orden_salida_detalles"] as $item) {
            $data = [
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "cantidad_fisica" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ];
            if ($item["id"] == 0) {
                $orden_salida->orden_salida_detalles()->create($data);
            } else {
                $orden_salida_detalle = OrdenSalidaDetalle::findOrFail($item["id"]);
                $orden_salida_detalle->update($data);
            }
        }

        if (isset($datos["eliminados_detalles"]) && !empty($datos["eliminados_detalles"])) {
            foreach ($datos["eliminados_detalles"] as $item) {
                $orden_salida_detalle = OrdenSalidaDetalle::findOrFail($item);
                $orden_salida_detalle->delete();
            }
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA ORDEN DE SALIDA", $old_orden_salida, $orden_salida, ["orden_salida_detalles"]);

        return $orden_salida;
    }


    public function aprobar(array $datos, OrdenSalida $orden_salida): OrdenSalida
    {
        $old_orden_salida = clone $orden_salida;
        $old_orden_salida->loadMissing(["orden_salida_detalles"]);
        $txtAprobado = $datos["verificado"] == 1 ? 'APROBADO' : 'APROBADO CON OBSERVACIONES';
        $orden_salida->update([
            "verificado" => $datos["verificado"],
            "estado" => $txtAprobado,
        ]);

        $almacen = Sucursal::where("almacen", 1)->get()->first();
        if (!$almacen) {
            throw new Exception("Error al actualizar el registro, no se encontró un Almacen");
        }

        foreach ($datos["orden_salida_detalles"] as $item) {
            $orden_salida_detalle = OrdenSalidaDetalle::findOrFail($item["id"]);
            $orden_salida_detalle->update([
                "verificado" => $item["verificado"],
                "sucursal_ajuste" => $item["cantidad"] == $item["cantidad_fisica"] ? $item["sucursal_ajuste"] : null,
                "motivo" => $item["cantidad"] == $item["cantidad_fisica"] ? $item["motivo"] : null,
            ]);

            $producto = Producto::findOrFail($item["producto_id"]);
            // VERIFICAR STOCK DEL PRODUCTO
            $resultado_stock = $this->sucursal_producto_service->verificaStockSucursalProducto($producto->id, $almacen->id, $item["cantidad_fisica"]);

            if (!$resultado_stock[0]) {
                throw new Exception("Stock insuficiente del producto " . $producto->nombre . " ; su stock actual es " . $resultado_stock[1]);
            }

            // DESCONTAR STOCK ALMACEN
            $this->kardex_producto_service->registroEgreso("ORDEN DE SALIDA", $producto, $item["cantidad_fisica"], $producto->precio, "EGRESO POR ORDEN DE SALIDA", $almacen->id, "OrdenSalidaDetalle", $orden_salida_detalle->id);

            // INCREMENTAR STOCK DE SUCURSAL DESTINO
            $this->kardex_producto_service->registroIngreso($orden_salida->sucursal_id, "ORDEN DE SALIDA", $producto, $item["cantidad_fisica"], $producto->precio, "INGRESO POR ORDEN DE SALIDA", "OrdenSalidaDetalle", $orden_salida_detalle->id);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "APROBO UNA ORDEN DE SALIDA", $old_orden_salida, $orden_salida, ["orden_salida_detalles"]);

        return $orden_salida;
    }

    /**
     * Eliminar orden_salida
     *
     * @param OrdenSalida $orden_salida
     * @return boolean
     */
    public function eliminar(OrdenSalida $orden_salida): bool|Exception
    {
        $old_orden_salida = clone $orden_salida;
        $orden_salida->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA ORDEN DE SALIDA", $old_orden_salida, null, ["orden_salida_detalles"]);
        return true;
    }
}
