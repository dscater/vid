<?php

namespace App\Services;

use App\Models\Producto;
use App\Services\HistorialAccionService;
use App\Models\Transferencia;
use App\Models\TransferenciaDetalle;
use App\Models\Sucursal;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TransferenciaService
{
    private $modulo = "TRANSFERENCIA";
    public function __construct(
        private HistorialAccionService $historialAccionService,
        private KardexProductoService $kardex_producto_service,
        private SucursalProductoService $sucursal_producto_service,
        private NotificacionService $notificacion_service,
        private SucursalService $sucursal_service,
        private AjusteService $ajuste_service
    ) {}

    public function listado(): Collection
    {
        $transferencias = Transferencia::select("transferencias.*")->where("usuarios", 1)->get();
        return $transferencias;
    }
    /**
     * Lista de transferencias paginado con filtros
     *
     * @param integer $length
     * @param integer $page
     * @param string $search
     * @param array $columnsSerachLike
     * @param array $columnsFilter
     * @return LengthAwarePaginator
     */
    public function listadoPaginado(int $length, int $page, string $search, array $columnsSerachLike = [], array $columnsFilter = [], array $columnsBetweenFilter = [], array $orderBy = [], array $realacionSearch = []): LengthAwarePaginator
    {
        $transferencias = Transferencia::select("transferencias.*")
            ->with(["sucursal:id,nombre", "sucursalDestino:id,nombre", "user_solicitante:id,nombre,paterno,materno", "user_aprobo:id,nombre,paterno,materno"]);

        if (Auth::user()->sucursal_asignada) {
            $sucursal_asignada = Auth::user()->sucursal_asignada;
            $transferencias->where(function ($query) use ($sucursal_asignada) {
                $query->where("sucursal_id", $sucursal_asignada->id)
                    ->orWhere("sucursal_destino", $sucursal_asignada->id);
            });
        }

        if (!empty($search) && !empty($realacionSearch)) {
            foreach ($realacionSearch as $relacion => $column) {
                $transferencias->whereHas($relacion, function ($query) use ($column, $search) {
                    $query->orWhere("$column", "LIKE", "%$search%");
                });
            }
        }

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $transferencias->where("transferencias.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $transferencias->whereBetween("transferencias.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $transferencias->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $transferencias->orderBy($value[0], $value[1]);
            }
        }


        $transferencias = $transferencias->paginate($length, ['*'], 'page', $page);
        return $transferencias;
    }

    /**
     * Crear transferencia
     *
     * @param array $datos
     * @return Transferencia
     */
    public function crear(array $datos): Transferencia
    {
        $nuevo_codigo = $this->generarCodigo();
        $transferencia = Transferencia::create([
            "nro" => $nuevo_codigo[0],
            "codigo" => $nuevo_codigo[1],
            "sucursal_id" => $datos["sucursal_id"],
            "user_sol" => $datos["user_sol"],
            "sucursal_destino" => $datos["sucursal_destino"],
            "user_ap" => $datos["user_ap"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "cantidad_total" => $datos["cantidad_total"],
            "cantidad_total_v" => $datos["cantidad_total"],
            "estado" => "PENDIENTE",
            "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["transferencia_detalles"] as $item) {
            // VERIFICAR STOCK DEL PRODUCTO
            $producto = Producto::findOrFail($item["producto_id"]);
            $resultado_stock = $this->sucursal_producto_service->verificaStockSucursalProducto($producto->id, $transferencia->sucursal_id, $item["cantidad"]);
            if (!$resultado_stock[0]) {
                throw new Exception("Stock insuficiente del producto " . $producto->nombre . " ; su stock actual es " . $resultado_stock[1]);
            }

            $transferencia->transferencia_detalles()->create([
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "cantidad_fisica" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA TRANSFERENCIA", $transferencia);

        return $transferencia;
    }

    public function generarCodigo()
    {
        $ultimo = Transferencia::orderBy("nro")->get()->last();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro + 1;
        }
        $codigo = "T." . $nro;
        return [$nro, $codigo];
    }

    /**
     * Actualizar transferencia
     *
     * @param array $datos
     * @param Transferencia $transferencia
     * @return Transferencia
     */
    public function actualizar(array $datos, Transferencia $transferencia): Transferencia
    {
        $old_transferencia = clone $transferencia;
        $old_transferencia->loadMissing(["transferencia_detalles"]);
        $transferencia->update([
            "sucursal_id" => $datos["sucursal_id"],
            "user_sol" => $datos["user_sol"],
            "sucursal_destino" => $datos["sucursal_destino"],
            "user_ap" => $datos["user_ap"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "cantidad_total" => $datos["cantidad_total"],
            "cantidad_total_v" => $datos["cantidad_total"],
            "estado" => "PENDIENTE",
            // "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["transferencia_detalles"] as $item) {
            $data = [
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "cantidad_fisica" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ];
            if ($item["id"] == 0) {
                $transferencia->transferencia_detalles()->create($data);
            } else {
                $transferencia_detalle = TransferenciaDetalle::findOrFail($item["id"]);
                $transferencia_detalle->update($data);
            }
        }

        if (isset($datos["eliminados_detalles"]) && !empty($datos["eliminados_detalles"])) {
            foreach ($datos["eliminados_detalles"] as $item) {
                $transferencia_detalle = TransferenciaDetalle::findOrFail($item);
                $transferencia_detalle->delete();
            }
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA TRANSFERENCIA", $old_transferencia, $transferencia, ["transferencia_detalles"]);

        return $transferencia;
    }

    public function aprobar(array $datos, Transferencia $transferencia): Transferencia
    {
        $old_transferencia = clone $transferencia;
        $old_transferencia->loadMissing(["transferencia_detalles"]);
        $txtAprobado = $datos["verificado"] == 1 ? 'APROBADO' : 'APROBADO CON OBSERVACIONES';
        $transferencia->update([
            "verificado" => $datos["verificado"],
            "estado" => $txtAprobado,
        ]);


        foreach ($datos["transferencia_detalles"] as $item) {
            $transferencia_detalle = TransferenciaDetalle::findOrFail($item["id"]);
            $transferencia_detalle->update([
                "verificado" => $item["verificado"],
                "cantidad_fisica" => $item["cantidad_fisica"],
                "sucursal_ajuste" => $item["cantidad"] != $item["cantidad_fisica"] ? $item["sucursal_ajuste"] : null,
                "motivo" => $item["cantidad"] != $item["cantidad_fisica"] ? $item["motivo"] : null,
            ]);

            $producto = Producto::findOrFail($item["producto_id"]);
            // VERIFICAR STOCK DEL PRODUCTO
            $resultado_stock = $this->sucursal_producto_service->verificaStockSucursalProducto($producto->id, $transferencia->sucursal_id, $item["cantidad_fisica"]);

            if (!$resultado_stock[0]) {
                throw new Exception("Stock insuficiente del producto " . $producto->nombre . " ; su stock actual es " . $resultado_stock[1]);
            }

            // DESCONTAR STOCK SUCURSAL
            $this->kardex_producto_service->registroEgreso("TRANSFERENCIA", $producto, $item["cantidad_fisica"], $producto->precio, "EGRESO POR TRANSFERENCIA", $transferencia->sucursal_id, "TransferenciaDetalle", $transferencia_detalle->id);

            // INCREMENTAR STOCK DEL ALMACEN
            $this->kardex_producto_service->registroIngreso($transferencia->sucursal_destino, "TRANSFERENCIA", $producto, $item["cantidad_fisica"], $producto->precio, "INGRESO POR TRANSFERENCIA", "TransferenciaDetalle", $transferencia_detalle->id);

            if ($item["cantidad"] != $item["cantidad_fisica"]) {
                // REGISTRAR AJUSTE
                $sucursal_ajuste = $this->sucursal_service->getSucursalAjuste();
                $ajuste = (float)$item["cantidad"] - (float)$item["cantidad_fisica"];
                $this->kardex_producto_service->registroIngreso($sucursal_ajuste->id, "TRANSFERENCIA", $producto, $ajuste, $item["costo"], "INGRESO POR AJUSTE", "TransferenciaDetalle", $transferencia_detalle->id);

                $this->ajuste_service->crear([
                    "sucursal_id" => $sucursal_ajuste->id,
                    "sucursal_origen" => $transferencia->sucursal_destino,
                    "producto_id" => $producto->id,
                    "cantidad" => $ajuste,
                    "motivo" => $item["motivo"],
                    "tipo" => "TRANSFERENCIA",
                    "registro_id" => $transferencia_detalle->id
                ]);
            }

            // VERIFICAR PARA NOTIFICACION
            $sucursal_producto = $this->sucursal_producto_service->getSucursalProducto($producto->id, $transferencia->sucursal_id);
            if ($sucursal_producto->stock_actual < $sucursal_producto->cantidad_minima) {
                $sucursal = Sucursal::findOrFail($transferencia->sucursal_id);
                $descripcion = 'STOCK DEL PRODUCTO <b>' . $producto->nombre . '"</b> (' . $sucursal_producto->stock_actual . ') POR DEBAJO DEL STOCK MÍNIMO (' . $sucursal_producto->cantidad_minima . '). Sucursal <b>' . $sucursal->nombre . '</b>';
                $notificacion = $this->notificacion_service->crear([
                    "descripcion" => $descripcion,
                    "modulo" => "SucursalProducto",
                    "modulo_id" => $sucursal_producto->id,
                ]);
                $this->notificacion_service->asignarNotificaciones($transferencia->sucursal_id, $notificacion);
            }
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "APROBO UNA TRANSFERENCIA", $old_transferencia, $transferencia, ["transferencia_detalles"]);

        return $transferencia;
    }

    /**
     * Eliminar transferencia
     *
     * @param Transferencia $transferencia
     * @return boolean
     */
    public function eliminar(Transferencia $transferencia): bool|Exception
    {
        $old_transferencia = clone $transferencia;
        $transferencia->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA TRANSFERENCIA", $old_transferencia, null, ["transferencia_detalles"]);
        return true;
    }
}
