<?php

namespace App\Services;

use App\Models\Producto;
use App\Services\HistorialAccionService;
use App\Models\Proforma;
use App\Models\ProformaDetalle;
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
            ->with(["proforma_detalles.producto:id,nombre", "proforma_detalles.unidad_medida:id,nombre", "cliente:id,razon_social,nit", "user:id,nombre,paterno,materno", "sucursal:id,nombre"]);

        $proformas = $proformas->get()->each
            ->append(["fecha_t", "hora_t", "fecha_c", "fecha_ct", "literal_txt"]);

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
            ->with(["sucursal:id,nombre", "user:id,nombre,paterno,materno", "cliente:id,razon_social"]);

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
            "sucursal_id" => $datos["sucursal_id"],
            "cliente_id" => $datos["cliente_id"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "cantidad_total" => $datos["cantidad_total"],
            "cs_f" => $datos["cs_f"],
            "forma_pago" => $datos["forma_pago"],
            // "cancelado" => $datos["cancelado"],
            // "cambio" => $datos["cambio"],
            "total" => $datos["total"],
            "total_st" => $datos["total_st"],
            // "solicitud_descuento" => $datos["solicitud_descuento"],
            "descuento" => $datos["descuento"],
            "total_f" => $datos["total_f"],
            "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["proforma_detalles"] as $item) {
            $proforma_detalle = $proforma->proforma_detalles()->create([
                "producto_id" => $item["producto_id"],
                "unidad_medida_id" => $item["unidad_medida_id"],
                "cantidad" => $item["cantidad"],
                "precio" => $item["precio"],
                "subtotal" => $item["subtotal"],
                "descuento" => $item["descuento"],
                "subtotal_f" => $item["subtotal_f"],
            ]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA PROFORMA", $proforma);

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
            "sucursal_id" => $datos["sucursal_id"],
            "cliente_id" => $datos["cliente_id"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "cantidad_total" => $datos["cantidad_total"],
            "cs_f" => $datos["cs_f"],
            "forma_pago" => $datos["forma_pago"],
            // "cancelado" => $datos["cancelado"],
            // "cambio" => $datos["cambio"],
            "total" => $datos["total"],
            "total_st" => $datos["total_st"],
            // "solicitud_descuento" => $datos["solicitud_descuento"],
            "descuento" => $datos["descuento"],
            "total_f" => $datos["total_f"],
        ]);

        foreach ($datos["proforma_detalles"] as $item) {
            $data = [
                "producto_id" => $item["producto_id"],
                "unidad_medida_id" => $item["unidad_medida_id"],
                "cantidad" => $item["cantidad"],
                "precio" => $item["precio"],
                "subtotal" => $item["subtotal"],
                "descuento" => $item["descuento"],
                "subtotal_f" => $item["subtotal_f"],
            ];
            if ($item["id"] == 0) {
                $proforma->proforma_detalles()->create($data);
            } else {
                $proforma_detalle = ProformaDetalle::findOrFail($item["id"]);
                $proforma_detalle->update($data);
            }

            if ($proforma->verificado == 2) {
                $producto = Producto::findOrFail($item["producto_id"]);
                // VERIFICAR STOCK DEL PRODUCTO
                $resultado_stock = $this->sucursal_producto_service->verificaStockSucursalProducto($producto->id, $datos["sucursal_id"], $item["cantidad"]);

                if (!$resultado_stock[0]) {
                    throw new Exception("Stock insuficiente del producto " . $producto->nombre . " ; su stock actual es " . $resultado_stock[1]);
                }

                // DESCONTAR STOCK DE SUCURSAL
                $this->kardex_producto_service->registroEgreso("PROFORMA", $producto, $item["cantidad"], $producto->precio, "EGRESO POR PROFORMA", $datos["sucursal_id"], "ProformaDetalle", $proforma_detalle->id);
            }
        }

        if (isset($datos["eliminados_detalles"]) && !empty($datos["eliminados_detalles"])) {
            foreach ($datos["eliminados_detalles"] as $item) {
                $proforma_detalle = ProformaDetalle::findOrFail($item);
                $proforma_detalle->delete();
            }
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA PROFORMA", $old_proforma, $proforma, ["proforma_detalles"]);
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
