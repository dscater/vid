<?php

namespace App\Services;

use App\Models\Producto;
use App\Services\HistorialAccionService;
use App\Models\Proforma;
use App\Models\ProformaDetalle;
use App\Models\ProformaDetalleProducto;
use App\Models\ProformaProducto;
use App\Models\Sucursal;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProformaService
{
    private $modulo = "PROFORMA";
    public function __construct(
        private HistorialAccionService $historialAccionService,
        private KardexProductoService $kardex_producto_service,
        private SucursalProductoService $sucursal_producto_service,
    ) {}

    public function listado(): Collection
    {
        $proformas = Proforma::select("proformas.*")
            ->with(["proforma_productos.producto.unidad_medida", "proforma_detalles.proforma_detalle_productos", "proforma_detalles.cliente",  "user:id,nombre,paterno,materno"]);

        $proformas = $proformas->get()->each
            ->append(["fecha_t", "hora_t", "fecha_c", "fecha_ct", "sucursals_txt"]);

        return $proformas;
    }
    /**
     * Lista de proformas paginado con filtros
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
        $proformas = Proforma::select("proformas.*")
            ->with(["user:id,nombre,paterno,materno"]);

        if (Auth::user()->sucursal_asignada) {
            $proformas->where("sucursal_id", Auth::user()->sucursal_asignada->id);
        }

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $proformas->where("proformas.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $proformas->whereBetween("proformas.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $proformas->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $proformas->orderBy($value[0], $value[1]);
            }
        }

        $proformas = $proformas->paginate($length, ['*'], 'page', $page);
        return $proformas;
    }

    /**
     * Crear proforma
     *
     * @param array $datos
     * @return Proforma
     */
    public function crear(array $datos): Proforma
    {
        $nuevo_codigo = $this->generarCodigo();
        $proforma = Proforma::create([
            "nro" => $nuevo_codigo[0],
            "codigo" => $nuevo_codigo[1],
            "sucursal_ids" => $datos["sucursal_ids"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "total" => $datos["total"],
            "user_id" => Auth::user()->id,
        ]);


        $ids_productos = [];
        foreach ($datos["proforma_productos"] as $item_producto) {
            $proforma_producto = ProformaProducto::create([
                "proforma_id" => $proforma->id,
                "producto_id" => $item_producto["producto_id"],
                "precio" => $item_producto["precio"],
                "unidad_medida_id" => $item_producto["unidad_medida_id"],
                "stock_actual" => $item_producto["stock_actual"],
            ]);
            $ids_productos[] = $proforma_producto->id;
        }

        foreach ($datos["proforma_detalles"] as $item) {
            // Log::debug($item);
            // DETALLE
            $proforma_detalle = $proforma->proforma_detalles()->create([
                "proforma_id" => $proforma->id,
                "cliente_id" => $item["cliente_id"],
                "cantidad" => $item["cantidad"],
                "total" => $item["total"],
                "saldo" => $item["total"],
                "estado" => "PENDIENTE",
            ]);
            // DETALLE PRODUCTOS
            foreach ($item["proforma_detalle_productos"] as $index => $pdp) {
                $proforma_detalle->proforma_detalle_productos()->create([
                    "proforma_id" => $proforma->id,
                    "proforma_detalle_id" => $proforma_detalle->id,
                    "proforma_producto_id" => $ids_productos[$index],
                    "producto_id" => $proforma_producto->producto_id,
                    "unidad_medida_id" => $proforma_producto->unidad_medida_id,
                    "cantidad" => $pdp["cantidad"],
                    "resta" => $pdp["cantidad"] ?? 0,
                    "precio" => $pdp["precio"],
                    "subtotal" => $pdp["subtotal"],
                ]);
            }
        }





        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA PROFORMA", $proforma, null, ["proforma_productos", "proforma_detalles"]);

        return $proforma;
    }

    public function generarCodigo()
    {
        $ultimo = Proforma::orderBy("nro")->get()->last();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro + 1;
        }
        $codigo = "PF." . $nro;
        return [$nro, $codigo];
    }

    /**
     * Actualizar proforma
     *
     * @param array $datos
     * @param Proforma $proforma
     * @return Proforma
     */
    public function actualizar(array $datos, Proforma $proforma): Proforma
    {
        $old_proforma = clone $proforma;
        $old_proforma->loadMissing(["proforma_detalles"]);
        $proforma->update([
            "sucursal_ids" => $datos["sucursal_ids"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "total" => $datos["total"],
        ]);

        $ids_productos = [];
        foreach ($datos["proforma_productos"] as $item_producto) {
            $data_proforma_producto = [
                "proforma_id" => $proforma->id,
                "producto_id" => $item_producto["producto_id"],
                "precio" => $item_producto["precio"],
                "unidad_medida_id" => $item_producto["unidad_medida_id"],
                "stock_actual" => $item_producto["stock_actual"],
            ];

            if ($item_producto["id"] && $item_producto["id"] != 0) {
                $proforma_producto = ProformaProducto::findOrFail($item_producto["id"]);
                $proforma_producto->update($data_proforma_producto);
            } else {
                $proforma_producto = ProformaProducto::create($data_proforma_producto);
            }
            $ids_productos[] = $proforma_producto->id;
        }

        foreach ($datos["proforma_detalles"] as $item) {
            $data_detalle = [
                "proforma_id" => $proforma->id,
                "cliente_id" => $item["cliente_id"],
                "cantidad" => $item["cantidad"],
                "total" => $item["total"],
                "saldo" => $item["total"],
                "estado" => "PENDIENTE",
            ];

            // Log::debug($item);
            // DETALLE
            if (isset($item["id"]) && $item["id"] && $item["id"] != 0) {
                $proforma_detalle = ProformaDetalle::findOrFail($item["id"]);
                $proforma_detalle->update($data_detalle);
            } else {
                $proforma_detalle = $proforma->proforma_detalles()->create($data_detalle);
            }

            // DETALLE PRODUCTOS
            foreach ($item["proforma_detalle_productos"] as $index => $pdp) {
                $data_detalle_producto = [
                    "proforma_id" => $proforma->id,
                    "proforma_detalle_id" => $proforma_detalle->id,
                    "proforma_producto_id" => $ids_productos[$index],
                    "producto_id" => $proforma_producto->producto_id,
                    "unidad_medida_id" => $proforma_producto->unidad_medida_id,
                    "cantidad" => $pdp["cantidad"],
                    "resta" => $pdp["cantidad"] ?? 0,
                    "cantidad_entregada" => $pdp["cantidad_entregada"],
                    "precio" => $pdp["precio"],
                    "subtotal" => $pdp["subtotal"],
                    "verificado" => $pdp["verificado"],
                ];

                if (isset($pdp["id"]) && $pdp["id"] && $pdp["id"] != 0) {
                    $proforma_detalle_producto = ProformaDetalleProducto::findOrFail($pdp["id"]);
                    $proforma_detalle_producto->update($data_detalle_producto);
                } else {
                    $proforma_detalle->proforma_detalle_productos()->create($data_detalle_producto);
                }
            }
        }

        if (isset($datos["eliminados_detalles"]) && !empty($datos["eliminados_detalles"])) {
            foreach ($datos["eliminados_detalles"] as $item) {
                $proforma_detalle = ProformaDetalle::findOrFail($item);
                $proforma_detalle->proforma_detalle_productos()->delete();
                $proforma_detalle->delete();
            }
        }

        if (isset($datos["eliminados_productos"]) && !empty($datos["eliminados_productos"])) {
            foreach ($datos["eliminados_productos"] as $item) {
                $proforma_producto = ProformaProducto::findOrFail($item);
                $proforma_producto->proforma_detalle_productos()->delete();
                $proforma_producto->delete();
            }
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA PROFORMA", $old_proforma, $proforma, ["proforma_productos", "proforma_detalles"]);
        return $proforma;
    }


    public function aprobar(array $datos, Proforma $proforma): Proforma
    {
        $old_proforma = clone $proforma;
        $old_proforma->loadMissing(["proforma_detalles"]);
        $proforma->update([
            "verificado" => 1,
            "solicitud_sw" => 1,
            "descuento" => $datos["descuento"],
            "estado" => "APROBADO",
            "user_ap" => Auth::user()->id,
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "APROBO EL DESCUENTO DE UNA PROFORMA", $old_proforma, $proforma, ["proforma_detalles"]);

        return $proforma;
    }

    /**
     * Eliminar proforma
     *
     * @param Proforma $proforma
     * @return boolean
     */
    public function eliminar(Proforma $proforma): bool|Exception
    {
        $old_proforma = clone $proforma;
        $proforma->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA PROFORMA", $old_proforma, null, ["proforma_detalles"]);
        return true;
    }
}
