<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Ajuste;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class AjusteService
{

    private $modulo = "AJUSTES";

    public function __construct(
        private HistorialAccionService $historialAccionService,
        private AjusteReposicionService $ajuste_reposicion_service,
        private KardexProductoService $kardex_producto_service
    ) {}

    public function listado(): Collection
    {
        $ajustes = Ajuste::select("ajustes.*")->get();
        return $ajustes;
    }
    /**
     * Lista de ajustes paginado con filtros
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
        $ajustes = Ajuste::select("ajustes.*")
            ->with(["producto:id,codigo,nombre", "sucursal", "oSucursalOrigen"])
            ->join("productos", "productos.id", "=", "ajustes.producto_id");

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $ajustes->where("ajustes.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $ajustes->whereBetween("ajustes.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $ajustes->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $ajustes->orderBy($value[0], $value[1]);
            }
        }


        $ajustes = $ajustes->paginate($length, ['*'], 'page', $page);
        return $ajustes;
    }

    /**
     * Crear ajuste
     *
     * @param array $datos
     * @return Ajuste
     */
    public function crear(array $datos): Ajuste
    {

        $ajuste = Ajuste::create([
            "sucursal_id" => $datos["sucursal_id"],
            "sucursal_origen" => $datos["sucursal_origen"],
            "producto_id" => $datos["producto_id"],
            "cantidad" => $datos["cantidad"],
            "motivo" => $datos["motivo"],
            "estado" => "NO REPUESTO",
            "tipo" => $datos["tipo"],
            "registro_id" => $datos["registro_id"] ?? NULL,
            "fecha" => date("Y-m-d")
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN AJUSTE", $ajuste);

        return $ajuste;
    }

    /**
     * Actualizar ajuste
     *
     * @param array $datos
     * @param Ajuste $ajuste
     * @return Ajuste
     */
    public function actualizar(array $datos, Ajuste $ajuste): Ajuste
    {
        $old_ajuste = Ajuste::find($ajuste->id);
        $ajuste->update([
            "estado" => "REPUESTO",
        ]);

        // verificar sucursal de reposición
        $almacen = Sucursal::where("almacen", 1)->get()->first();
        if ($ajuste->tipo == 'DEVOLUCION DE STOCK') {
            if (!$almacen) {
                throw new Exception("Error al actualizar el registro, no se encontró un Almacen");
            }
        }

        if ($ajuste->tipo == 'SOLICITUD DE INGRESO') {
            if (!$almacen) {
                throw new Exception("Error al actualizar el registro, no se encontró un Almacen");
            }
        }

        if ($ajuste->tipo == 'TRANSFERENCIA') {
            $almacen = $ajuste->oSucursalOrigen;
        }

        if ($ajuste->tipo == 'ORDEN DE SALIDA') {
            $almacen = $ajuste->oSucursalOrigen;
        }

        $ajuste_reposicion = $this->ajuste_reposicion_service->crear([
            "ajuste_id" => $ajuste->id,
            "sucursal_id" => $datos["sucursal_origen"],
            "producto_id" => $ajuste->producto_id,
            "cantidad" => $ajuste->cantidad,
        ]);

        // INCREMENTAR STOCK DEL ALMACEN CENTRAL CANTIDAD
        $producto = Producto::findOrFail($ajuste->producto_id);
        $this->kardex_producto_service->registroIngreso($almacen->id, "AJUSTE REPOSICIÓN", $producto, $ajuste->cantidad, $producto->precio, "INGRESO POR REPOSICIÓN DE AJUSTE", "AjusteReposicion", $ajuste_reposicion->id);

        // DESCONTAR STOCK ALMACEN AJUSTE
        $almacen_ajuste = Sucursal::where("almacen", 2)->get()->first();
        if (!$almacen_ajuste) {
            throw new Exception("Error al actualizar el registro, no se encontró un Almacen AJUSTES");
        }

        $this->kardex_producto_service->registroEgreso("AJUSTE REPOSICIÓN", $producto, $ajuste->cantidad, $producto->precio, "EGRESO POR REPOSICIÓN DE AJUSTE", $almacen_ajuste->id, "AjusteReposicion", $ajuste_reposicion->id);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "REPOSICIÓN DE PRODUCTO", $old_ajuste, $ajuste);

        return $ajuste;
    }
}
