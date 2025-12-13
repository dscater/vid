<?php

namespace App\Services;

use App\Models\Producto;
use App\Services\HistorialAccionService;
use App\Models\OrdenVenta;
use App\Models\OrdenVentaDetalle;
use App\Models\Sucursal;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrdenVentaService
{
    private $modulo = "ORDEN DE VENTA";
    public function __construct(
        private HistorialAccionService $historialAccionService,
        private KardexProductoService $kardex_producto_service,
        private SucursalProductoService $sucursal_producto_service,
        private CuentaCobrarService $cuenta_cobrar_service,
        private ParametroClienteService $parametro_cliente_service
    ) {}

    public function listado(): Collection
    {
        $orden_ventas = OrdenVenta::select("orden_ventas.*")
            ->with((["orden_venta_detalles.producto:id,nombre", "orden_venta_detalles.unidad_medida:id,nombre", "cliente:id,razon_social,nit", "user:id,nombre,paterno,materno", "sucursal:id,nombre"]));

        $orden_ventas = $orden_ventas->get()->each
            ->append(["fecha_t", "hora_t", "fecha_c", "fecha_ct", "literal_txt"]);


        return $orden_ventas;
    }
    /**
     * Lista de orden_ventas paginado con filtros
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
        $orden_ventas = OrdenVenta::select("orden_ventas.*")
            ->with(["sucursal:id,nombre", "user:id,nombre,paterno,materno", "cliente:id,razon_social"]);
        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $orden_ventas->where("orden_ventas.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $orden_ventas->whereBetween("orden_ventas.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $orden_ventas->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $orden_ventas->orderBy($value[0], $value[1]);
            }
        }

        $orden_ventas = $orden_ventas->paginate($length, ['*'], 'page', $page);
        return $orden_ventas;
    }

    /**
     * Crear orden_venta
     *
     * @param array $datos
     * @return OrdenVenta
     */
    public function crear(array $datos): OrdenVenta
    {
        $nuevo_codigo = $this->generarCodigo();
        $orden_venta = OrdenVenta::create([
            "nro" => $nuevo_codigo[0],
            "codigo" => $nuevo_codigo[1],
            "sucursal_id" => $datos["sucursal_id"],
            "cliente_id" => $datos["cliente_id"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "cantidad_total" => $datos["cantidad_total"],
            "cs_f" => $datos["cs_f"],
            "forma_pago" => $datos["forma_pago"],
            "cancelado" => $datos["cancelado"],
            "cambio" => $datos["cambio"],
            "total" => $datos["total"],
            "total_st" => $datos["total_st"],
            "solicitud_descuento" => $datos["solicitud_descuento"],
            "solicitud_sw" => $datos["solicitud_descuento"] == 1 ? 0 : NULL,
            "monto_solicitud" => $datos["solicitud_descuento"] == 1 ? $datos["descuento"] : NULL,
            "descuento" => $datos["solicitud_descuento"] == 1 ? $datos["descuento"] : NULL,
            "total_f" => $datos["total_f"],
            "estado" => $datos["solicitud_descuento"] == 1 ? "PENDIENTE" : "FINALIZADO",
            "verificado" => $datos["solicitud_descuento"] == 1 ? 0 : 2,
            "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["orden_venta_detalles"] as $item) {
            $orden_venta_detalle = $orden_venta->orden_venta_detalles()->create([
                "producto_id" => $item["producto_id"],
                "unidad_medida_id" => $item["unidad_medida_id"],
                "cantidad" => $item["cantidad"],
                "precio" => $item["precio"],
                "subtotal" => $item["subtotal"],
                "descuento" => $item["descuento"],
                "subtotal_f" => $item["subtotal_f"],
            ]);

            if ($orden_venta->verificado == 2) {
                $producto = Producto::findOrFail($item["producto_id"]);
                // VERIFICAR STOCK DEL PRODUCTO
                $resultado_stock = $this->sucursal_producto_service->verificaStockSucursalProducto($producto->id, $datos["sucursal_id"], $item["cantidad"]);

                if (!$resultado_stock[0]) {
                    throw new Exception("Stock insuficiente del producto " . $producto->nombre . " ; su stock actual es " . $resultado_stock[1]);
                }

                // DESCONTAR STOCK DE SUCURSAL
                $this->kardex_producto_service->registroEgreso("ORDEN DE VENTA", $producto, $item["cantidad"], $producto->precio, "EGRESO POR ORDEN DE VENTA", $datos["sucursal_id"], "OrdenVentaDetalle", $orden_venta_detalle->id);
            }
        }

        // TIPO DE PAGO
        if ($orden_venta->forma_pago == 'CRÉDITO') {
            $this->cuenta_cobrar_service->nuevo($orden_venta);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA ORDEN DE VENTA", $orden_venta);

        // PARAMETRO CLIENTE
        $this->parametro_cliente_service->verificarRankCliente($orden_venta->cliente_id);

        return $orden_venta;
    }

    public function generarCodigo()
    {
        $ultimo = OrdenVenta::orderBy("nro")->get()->last();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro + 1;
        }
        $codigo = "OV." . $nro;
        return [$nro, $codigo];
    }

    /**
     * Actualizar orden_venta
     *
     * @param array $datos
     * @param OrdenVenta $orden_venta
     * @return OrdenVenta
     */
    public function actualizar(array $datos, OrdenVenta $orden_venta): OrdenVenta
    {
        $old_orden_venta = clone $orden_venta;
        $old_orden_venta->loadMissing(["orden_venta_detalles"]);
        $orden_venta->update([
            "sucursal_id" => $datos["sucursal_id"],
            "cliente_id" => $datos["cliente_id"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "cantidad_total" => $datos["cantidad_total"],
            "cs_f" => $datos["cs_f"],
            "forma_pago" => $datos["forma_pago"],
            "cancelado" => $datos["cancelado"],
            "cambio" => $datos["cambio"],
            "total" => $datos["total"],
            "total_st" => $datos["total_st"],
            "monto_solicitud" => $datos["solicitud_descuento"] == 1 ? $datos["descuento"] : NULL,
            "descuento" => $datos["solicitud_descuento"] == 1 ? $datos["descuento"] : NULL,
            "total_f" => $datos["total_f"],
            "estado" => $datos["solicitud_descuento"] == 1 && $orden_venta->solicitud_sw == 0 ? "PENDIENTE" : "FINALIZADO",
            "verificado" => $datos["solicitud_descuento"] == 1 && $orden_venta->solicitud_sw == 0 ?  0 : 2,
        ]);

        foreach ($datos["orden_venta_detalles"] as $item) {
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
                $orden_venta->orden_venta_detalles()->create($data);
            } else {
                $orden_venta_detalle = OrdenVentaDetalle::findOrFail($item["id"]);
                $orden_venta_detalle->update($data);
            }

            if ($orden_venta->verificado == 2) {
                $producto = Producto::findOrFail($item["producto_id"]);
                // VERIFICAR STOCK DEL PRODUCTO
                $resultado_stock = $this->sucursal_producto_service->verificaStockSucursalProducto($producto->id, $datos["sucursal_id"], $item["cantidad"]);

                if (!$resultado_stock[0]) {
                    throw new Exception("Stock insuficiente del producto " . $producto->nombre . " ; su stock actual es " . $resultado_stock[1]);
                }

                // DESCONTAR STOCK DE SUCURSAL
                $this->kardex_producto_service->registroEgreso("ORDEN DE VENTA", $producto, $item["cantidad"], $producto->precio, "EGRESO POR ORDEN DE VENTA", $datos["sucursal_id"], "OrdenVentaDetalle", $orden_venta_detalle->id);
            }
        }

        if (isset($datos["eliminados_detalles"]) && !empty($datos["eliminados_detalles"])) {
            foreach ($datos["eliminados_detalles"] as $item) {
                $orden_venta_detalle = OrdenVentaDetalle::findOrFail($item);
                $orden_venta_detalle->delete();
            }
        }

        // TIPO DE PAGO
        if ($orden_venta->forma_pago == 'CRÉDITO') {
            $this->cuenta_cobrar_service->nuevo($orden_venta);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA ORDEN DE VENTA", $old_orden_venta, $orden_venta, ["orden_venta_detalles"]);
        return $orden_venta;
    }


    public function aprobar(array $datos, OrdenVenta $orden_venta): OrdenVenta
    {
        $old_orden_venta = clone $orden_venta;
        $old_orden_venta->loadMissing(["orden_venta_detalles"]);
        $orden_venta->update([
            "verificado" => 1,
            "solicitud_sw" => 1,
            "descuento" => $datos["descuento"],
            "estado" => "APROBADO",
            "user_ap" => Auth::user()->id,
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "APROBO EL DESCUENTO DE UNA ORDEN DE VENTA", $old_orden_venta, $orden_venta, ["orden_venta_detalles"]);

        return $orden_venta;
    }

    /**
     * Eliminar orden_venta
     *
     * @param OrdenVenta $orden_venta
     * @return boolean
     */
    public function eliminar(OrdenVenta $orden_venta): bool|Exception
    {
        $old_orden_venta = clone $orden_venta;
        $orden_venta->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA ORDEN DE VENTA", $old_orden_venta, null, ["orden_venta_detalles"]);
        return true;
    }
}
