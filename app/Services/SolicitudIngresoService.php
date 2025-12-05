<?php

namespace App\Services;

use App\Models\Producto;
use App\Services\HistorialAccionService;
use App\Models\SolicitudIngreso;
use App\Models\SolicitudIngresoDetalle;
use App\Models\Sucursal;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SolicitudIngresoService
{
    private $modulo = "SOLICITUD DE INGRESO";
    public function __construct(
        private HistorialAccionService $historialAccionService,
        private KardexProductoService $kardex_producto_service
    ) {}

    public function listado(): Collection
    {
        $solicitud_ingresos = SolicitudIngreso::select("solicitud_ingresos.*")->where("usuarios", 1)->get();
        return $solicitud_ingresos;
    }
    /**
     * Lista de solicitud_ingresos paginado con filtros
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
        $solicitud_ingresos = SolicitudIngreso::select("solicitud_ingresos.*")
            ->with(["proveedor:id,razon_social", "user:id,nombre,paterno,materno"]);
        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $solicitud_ingresos->where("solicitud_ingresos.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $solicitud_ingresos->whereBetween("solicitud_ingresos.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $solicitud_ingresos->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $solicitud_ingresos->orderBy($value[0], $value[1]);
            }
        }


        $solicitud_ingresos = $solicitud_ingresos->paginate($length, ['*'], 'page', $page);
        return $solicitud_ingresos;
    }

    /**
     * Crear solicitud_ingreso
     *
     * @param array $datos
     * @return SolicitudIngreso
     */
    public function crear(array $datos): SolicitudIngreso
    {

        $nuevo_codigo = $this->generarCodigoSolicitud();
        $solicitud_ingreso = SolicitudIngreso::create([
            "nro" => $nuevo_codigo[0],
            "codigo" => $nuevo_codigo[1],
            "proveedor_id" => mb_strtoupper($datos["proveedor_id"]),
            "fecha_ingreso" => $datos["fecha_ingreso"],
            "hora_ingreso" => $datos["hora_ingreso"],
            "fecha_sis" => date("Y-m-d"),
            "hora_sis" => date("H:i"),
            "cs_f" => $datos["cs_f"],
            "tipo_cambio" => $datos["tipo_cambio"],
            "gastos" => $datos["gastos"],
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "cantidad_total" => $datos["cantidad_total"],
            "total" => $datos["total"],
            "estado" => "PENDIENTE",
            "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["solicitud_ingreso_detalles"] as $item) {
            $solicitud_ingreso->solicitud_ingreso_detalles()->create([
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "cantidad_fisica" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA SOLICITUD DE INGRESO", $solicitud_ingreso);

        return $solicitud_ingreso;
    }

    public function generarCodigoSolicitud()
    {
        $ultimo = SolicitudIngreso::orderBy("nro")->get()->last();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro + 1;
        }
        $codigo = "SOL." . $nro;
        return [$nro, $codigo];
    }

    /**
     * Actualizar solicitud_ingreso
     *
     * @param array $datos
     * @param SolicitudIngreso $solicitud_ingreso
     * @return SolicitudIngreso
     */
    public function actualizar(array $datos, SolicitudIngreso $solicitud_ingreso): SolicitudIngreso
    {
        $old_solicitud_ingreso = clone $solicitud_ingreso;
        $old_solicitud_ingreso->loadMissing(["solicitud_ingreso_detalles"]);
        $solicitud_ingreso->update([
            "proveedor_id" => mb_strtoupper($datos["proveedor_id"]),
            "fecha_ingreso" => $datos["fecha_ingreso"],
            "hora_ingreso" => $datos["hora_ingreso"],
            "fecha_sis" => date("Y-m-d"),
            "hora_sis" => date("H:i"),
            "cs_f" => $datos["cs_f"],
            "tipo_cambio" => $datos["tipo_cambio"],
            "gastos" => $datos["gastos"],
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "cantidad_total" => $datos["cantidad_total"],
            "total" => $datos["total"],
            "estado" => "PENDIENTE",
            "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["solicitud_ingreso_detalles"] as $item) {
            $data = [
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "cantidad_fisica" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ];
            if ($item["id"] == 0) {
                $solicitud_ingreso->solicitud_ingreso_detalles()->create($data);
            } else {
                $solicitud_ingreso_detalle = SolicitudIngresoDetalle::findOrFail($item["id"]);
                $solicitud_ingreso_detalle->update($data);
            }
        }

        if (isset($datos["eliminados_detalles"]) && !empty($datos["eliminados_detalles"])) {
            foreach ($datos["eliminados_detalles"] as $item) {
                $solicitud_ingreso_detalle = SolicitudIngresoDetalle::findOrFail($item);
                $solicitud_ingreso_detalle->delete();
            }
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA SOLICITUD DE INGRESO", $old_solicitud_ingreso, $solicitud_ingreso, ["solicitud_ingreso_detalles"]);

        return $solicitud_ingreso;
    }


    public function aprobar(array $datos, SolicitudIngreso $solicitud_ingreso): SolicitudIngreso
    {
        $old_solicitud_ingreso = clone $solicitud_ingreso;
        $old_solicitud_ingreso->loadMissing(["solicitud_ingreso_detalles"]);
        $txtAprobado = $datos["verificado"] == 1 ? 'APROBADO' : 'APROBADO CON OBSERVACIONES';
        $solicitud_ingreso->update([
            "verificado" => $datos["verificado"],
            "estado" => $txtAprobado,
        ]);

        $almacen = Sucursal::where("almacen", 1)->get()->first();
        if (!$almacen) {
            throw new Exception("Error al actualizar el registro, no se encontró un Almacen");
        }

        foreach ($datos["solicitud_ingreso_detalles"] as $item) {
            $solicitud_ingreso_detalle = SolicitudIngresoDetalle::findOrFail($item["id"]);
            $solicitud_ingreso_detalle->update([
                "verificado" => $item["verificado"],
                "sucursal_ajuste" => $item["cantidad"] == $item["cantidad_fisica"] ? $item["sucursal_ajuste"] : null,
                "motivo" => $item["cantidad"] == $item["cantidad_fisica"] ? $item["motivo"] : null,
            ]);

            // AUMENTAR STOCK ALMACEN
            $producto = Producto::findOrFail($item["producto_id"]);
            $this->kardex_producto_service->registroIngreso($almacen->id, "SOLICITUD INGRESO", $producto, $item["cantidad_fisica"], $producto->precio, "INGRESO POR SOLICITUD", "SolicitudIngresoDetalle", $solicitud_ingreso_detalle->id);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "APROBO UNA SOLICITUD DE INGRESO", $old_solicitud_ingreso, $solicitud_ingreso, ["solicitud_ingreso_detalles"]);

        return $solicitud_ingreso;
    }

    /**
     * Eliminar solicitud_ingreso
     *
     * @param SolicitudIngreso $solicitud_ingreso
     * @return boolean
     */
    public function eliminar(SolicitudIngreso $solicitud_ingreso): bool|Exception
    {
        $old_solicitud_ingreso = clone $solicitud_ingreso;
        $solicitud_ingreso->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA SOLICITUD DE INGRESO", $old_solicitud_ingreso, null, ["solicitud_ingreso_detalles"]);
        return true;
    }
}
