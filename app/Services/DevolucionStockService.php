<?php

namespace App\Services;

use App\Models\Producto;
use App\Services\HistorialAccionService;
use App\Models\DevolucionStock;
use App\Models\DevolucionStockDetalle;
use App\Models\Sucursal;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DevolucionStockService
{
    private $modulo = "DEVOLUCIÓN DE STOCK";
    public function __construct(
        private HistorialAccionService $historialAccionService,
        private KardexProductoService $kardex_producto_service,
        private SucursalProductoService $sucursal_producto_service
    ) {}

    public function listado(): Collection
    {
        $devolucion_stocks = DevolucionStock::select("devolucion_stocks.*")->where("usuarios", 1)->get();
        return $devolucion_stocks;
    }
    /**
     * Lista de devolucion_stocks paginado con filtros
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
        $devolucion_stocks = DevolucionStock::select("devolucion_stocks.*")
            ->with(["sucursal:id,nombre", "user:id,nombre,paterno,materno"]);
        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $devolucion_stocks->where("devolucion_stocks.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $devolucion_stocks->whereBetween("devolucion_stocks.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $devolucion_stocks->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $devolucion_stocks->orderBy($value[0], $value[1]);
            }
        }


        $devolucion_stocks = $devolucion_stocks->paginate($length, ['*'], 'page', $page);
        return $devolucion_stocks;
    }

    /**
     * Crear devolucion_stock
     *
     * @param array $datos
     * @return DevolucionStock
     */
    public function crear(array $datos): DevolucionStock
    {
        $nuevo_codigo = $this->generarCodigo();
        $devolucion_stock = DevolucionStock::create([
            "nro" => $nuevo_codigo[0],
            "codigo" => $nuevo_codigo[1],
            "sucursal_id" => $datos["sucursal_id"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "cantidad_total" => $datos["cantidad_total"],
            "total" => $datos["total"],
            "cantidad_total_v" => $datos["cantidad_total"],
            "total_v" => $datos["total"],
            "estado" => "PENDIENTE",
            "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["devolucion_stock_detalles"] as $item) {
            $devolucion_stock->devolucion_stock_detalles()->create([
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "cantidad_fisica" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA DEVOLUCIÓN DE STOCK", $devolucion_stock);

        return $devolucion_stock;
    }

    public function generarCodigo()
    {
        $ultimo = DevolucionStock::orderBy("nro")->get()->last();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro + 1;
        }
        $codigo = "DEV." . $nro;
        return [$nro, $codigo];
    }

    /**
     * Actualizar devolucion_stock
     *
     * @param array $datos
     * @param DevolucionStock $devolucion_stock
     * @return DevolucionStock
     */
    public function actualizar(array $datos, DevolucionStock $devolucion_stock): DevolucionStock
    {
        $old_devolucion_stock = clone $devolucion_stock;
        $old_devolucion_stock->loadMissing(["devolucion_stock_detalles"]);
        $devolucion_stock->update([
            "sucursal_id" => $datos["sucursal_id"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "cantidad_total" => $datos["cantidad_total"],
            "total" => $datos["total"],
            "cantidad_total_v" => $datos["cantidad_total"],
            "total_v" => $datos["total"],
            "estado" => "PENDIENTE",
            // "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["devolucion_stock_detalles"] as $item) {
            $data = [
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "cantidad_fisica" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ];
            if ($item["id"] == 0) {
                $devolucion_stock->devolucion_stock_detalles()->create($data);
            } else {
                $devolucion_stock_detalle = DevolucionStockDetalle::findOrFail($item["id"]);
                $devolucion_stock_detalle->update($data);
            }
        }

        if (isset($datos["eliminados_detalles"]) && !empty($datos["eliminados_detalles"])) {
            foreach ($datos["eliminados_detalles"] as $item) {
                $devolucion_stock_detalle = DevolucionStockDetalle::findOrFail($item);
                $devolucion_stock_detalle->delete();
            }
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA DEVOLUCIÓN DE STOCK", $old_devolucion_stock, $devolucion_stock, ["devolucion_stock_detalles"]);

        return $devolucion_stock;
    }


    public function aprobar(array $datos, DevolucionStock $devolucion_stock): DevolucionStock
    {
        $old_devolucion_stock = clone $devolucion_stock;
        $old_devolucion_stock->loadMissing(["devolucion_stock_detalles"]);
        $txtAprobado = $datos["verificado"] == 1 ? 'APROBADO' : 'APROBADO CON OBSERVACIONES';
        $devolucion_stock->update([
            "verificado" => $datos["verificado"],
            "estado" => $txtAprobado,
        ]);

        $almacen = Sucursal::where("almacen", 1)->get()->first();
        if (!$almacen) {
            throw new Exception("Error al actualizar el registro, no se encontró un Almacen");
        }

        foreach ($datos["devolucion_stock_detalles"] as $item) {
            $devolucion_stock_detalle = DevolucionStockDetalle::findOrFail($item["id"]);
            $devolucion_stock_detalle->update([
                "verificado" => $item["verificado"],
                "sucursal_ajuste" => $item["cantidad"] == $item["cantidad_fisica"] ? $item["sucursal_ajuste"] : null,
                "motivo" => $item["cantidad"] == $item["cantidad_fisica"] ? $item["motivo"] : null,
            ]);

            $producto = Producto::findOrFail($item["producto_id"]);
            // VERIFICAR STOCK DEL PRODUCTO
            $resultado_stock = $this->sucursal_producto_service->verificaStockSucursalProducto($producto->id, $devolucion_stock->sucursal_id, $item["cantidad_fisica"]);

            if (!$resultado_stock[0]) {
                throw new Exception("Stock insuficiente del producto " . $producto->nombre . " ; su stock actual es " . $resultado_stock[1]);
            }

            // DESCONTAR STOCK SUCURSAL
            $this->kardex_producto_service->registroEgreso("DEVOLUCIÓN DE STOCK", $producto, $item["cantidad_fisica"], $producto->precio, "EGRESO POR DEVOLUCIÓN DE STOCK", $devolucion_stock->sucursal_id, "DevolucionStockDetalle", $devolucion_stock_detalle->id);

            // INCREMENTAR STOCK DEL ALMACEN
            $this->kardex_producto_service->registroIngreso($almacen->id, "DEVOLUCIÓN DE STOCK", $producto, $item["cantidad_fisica"], $producto->precio, "INGRESO POR DEVOLUCIÓN DE STOCK", "DevolucionStockDetalle", $devolucion_stock_detalle->id);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "APROBO UNA DEVOLUCIÓN DE STOCK", $old_devolucion_stock, $devolucion_stock, ["devolucion_stock_detalles"]);

        return $devolucion_stock;
    }

    /**
     * Eliminar devolucion_stock
     *
     * @param DevolucionStock $devolucion_stock
     * @return boolean
     */
    public function eliminar(DevolucionStock $devolucion_stock): bool|Exception
    {
        $old_devolucion_stock = clone $devolucion_stock;
        $devolucion_stock->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA DEVOLUCIÓN DE STOCK", $old_devolucion_stock, null, ["devolucion_stock_detalles"]);
        return true;
    }
}
