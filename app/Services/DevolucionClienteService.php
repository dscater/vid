<?php

namespace App\Services;

use App\Models\Producto;
use App\Services\HistorialAccionService;
use App\Models\DevolucionCliente;
use App\Models\DevolucionClienteDetalle;
use App\Models\Sucursal;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DevolucionClienteService
{
    private $modulo = "DEVOLUCIÓN DE CLIENTES";
    public function __construct(
        private HistorialAccionService $historialAccionService,
        private KardexProductoService $kardex_producto_service,
        private SucursalProductoService $sucursal_producto_service
    ) {}

    public function listado(): Collection
    {
        $devolucion_clientes = DevolucionCliente::select("devolucion_clientes.*")
            ->with(["devolucion_cliente_detalles.producto", "user:id,nombre,paterno,materno", "sucursal:id,nombre", "cliente:id,razon_social"]);
        $devolucion_clientes = $devolucion_clientes->get();
        return $devolucion_clientes;
    }
    /**
     * Lista de devolucion_clientes paginado con filtros
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
        $devolucion_clientes = DevolucionCliente::select("devolucion_clientes.*")
            ->with(["sucursal:id,nombre", "user:id,nombre,paterno,materno", "cliente:id,razon_social"]);

        if (Auth::user()->sucursal_asignada) {
            $devolucion_clientes->where("sucursal_id", Auth::user()->sucursal_asignada->id);
        }

        if (!empty($search))
            $devolucion_clientes->whereHas("cliente", function ($query) use ($search) {
                $query->where("razon_social", "LIKE", "%$search%");
            });

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $devolucion_clientes->where("devolucion_clientes.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $devolucion_clientes->whereBetween("devolucion_clientes.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $devolucion_clientes->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $devolucion_clientes->orderBy($value[0], $value[1]);
            }
        }


        $devolucion_clientes = $devolucion_clientes->paginate($length, ['*'], 'page', $page);
        return $devolucion_clientes;
    }

    /**
     * Crear devolucion_cliente
     *
     * @param array $datos
     * @return DevolucionCliente
     */
    public function crear(array $datos): DevolucionCliente
    {
        $devolucion_cliente = DevolucionCliente::create([
            "sucursal_id" => $datos["sucursal_id"],
            "cliente_id" => $datos["cliente_id"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "cantidad_total" => $datos["cantidad_total"],
            "total" => $datos["total"],
            "user_id" => Auth::user()->id,
        ]);

        foreach ($datos["devolucion_cliente_detalles"] as $item) {
            $devolucion_cliente_detalle = $devolucion_cliente->devolucion_cliente_detalles()->create([
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ]);

            // INCREMENTAR STOCK DE SUCURSAL DESTINO
            $producto = Producto::findOrFail($item["producto_id"]);
            $this->kardex_producto_service->registroIngreso($devolucion_cliente->sucursal_id, "DEVOLUCIÓN DE CLIENTES", $producto, $item["cantidad"], $producto->precio, "INGRESO POR DEVOLUCIÓN DE CLIENTES", "DevolucionClienteDetalle", $devolucion_cliente_detalle->id);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA DEVOLUCIÓN DE CLIENTES", $devolucion_cliente);

        return $devolucion_cliente;
    }

    public function generarCodigo()
    {
        $ultimo = DevolucionCliente::orderBy("nro")->get()->last();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro + 1;
        }
        $codigo = "DEV." . $nro;
        return [$nro, $codigo];
    }

    /**
     * Actualizar devolucion_cliente
     *
     * @param array $datos
     * @param DevolucionCliente $devolucion_cliente
     * @return DevolucionCliente
     */
    public function actualizar(array $datos, DevolucionCliente $devolucion_cliente): DevolucionCliente
    {
        $old_devolucion_cliente = clone $devolucion_cliente;
        $old_devolucion_cliente->loadMissing(["devolucion_cliente_detalles"]);
        $devolucion_cliente->update([
            "sucursal_id" => $datos["sucursal_id"],
            "cliente_id" => $datos["cliente_id"],
            "fecha" => $datos["fecha"],
            "hora" => $datos["hora"],
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "cantidad_total" => $datos["cantidad_total"],
            "total" => $datos["total"],
        ]);

        foreach ($datos["devolucion_cliente_detalles"] as $item) {
            $data = [
                "producto_id" => $item["producto_id"],
                "cantidad" => $item["cantidad"],
                "costo" => $item["costo"],
                "subtotal" => $item["subtotal"],
            ];
            if ($item["id"] == 0) {
                $devolucion_cliente->devolucion_cliente_detalles()->create($data);
            } else {
                $devolucion_cliente_detalle = DevolucionClienteDetalle::findOrFail($item["id"]);
                $devolucion_cliente_detalle->update($data);
            }
        }

        if (isset($datos["eliminados_detalles"]) && !empty($datos["eliminados_detalles"])) {
            foreach ($datos["eliminados_detalles"] as $item) {
                $devolucion_cliente_detalle = DevolucionClienteDetalle::findOrFail($item);
                $devolucion_cliente_detalle->delete();
            }
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA DEVOLUCIÓN DE CLIENTES", $old_devolucion_cliente, $devolucion_cliente, ["devolucion_cliente_detalles"]);

        return $devolucion_cliente;
    }


    public function aprobar(array $datos, DevolucionCliente $devolucion_cliente): DevolucionCliente
    {
        $old_devolucion_cliente = clone $devolucion_cliente;
        $old_devolucion_cliente->loadMissing(["devolucion_cliente_detalles"]);
        $txtAprobado = $datos["verificado"] == 1 ? 'APROBADO' : 'APROBADO CON OBSERVACIONES';
        $devolucion_cliente->update([
            "verificado" => $datos["verificado"],
            "estado" => $txtAprobado,
        ]);

        $almacen = Sucursal::where("almacen", 1)->get()->first();
        if (!$almacen) {
            throw new Exception("Error al actualizar el registro, no se encontró un Almacen");
        }

        foreach ($datos["devolucion_cliente_detalles"] as $item) {
            $devolucion_cliente_detalle = DevolucionClienteDetalle::findOrFail($item["id"]);
            $devolucion_cliente_detalle->update([
                "verificado" => $item["verificado"],
                "sucursal_ajuste" => $item["cantidad"] == $item["cantidad_fisica"] ? $item["sucursal_ajuste"] : null,
                "motivo" => $item["cantidad"] == $item["cantidad_fisica"] ? $item["motivo"] : null,
            ]);

            $producto = Producto::findOrFail($item["producto_id"]);
            // VERIFICAR STOCK DEL PRODUCTO
            $resultado_stock = $this->sucursal_producto_service->verificaStockSucursalProducto($producto->id, $devolucion_cliente->sucursal_id, $item["cantidad_fisica"]);

            if (!$resultado_stock[0]) {
                throw new Exception("Stock insuficiente del producto " . $producto->nombre . " ; su stock actual es " . $resultado_stock[1]);
            }

            // DESCONTAR STOCK SUCURSAL
            $this->kardex_producto_service->registroEgreso("DEVOLUCIÓN DE CLIENTES", $producto, $item["cantidad_fisica"], $producto->precio, "EGRESO POR DEVOLUCIÓN DE CLIENTES", $devolucion_cliente->sucursal_id, "DevolucionClienteDetalle", $devolucion_cliente_detalle->id);

            // INCREMENTAR STOCK DEL ALMACEN
            $this->kardex_producto_service->registroIngreso($almacen->id, "DEVOLUCIÓN DE CLIENTES", $producto, $item["cantidad_fisica"], $producto->precio, "INGRESO POR DEVOLUCIÓN DE CLIENTES", "DevolucionClienteDetalle", $devolucion_cliente_detalle->id);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "APROBO UNA DEVOLUCIÓN DE CLIENTES", $old_devolucion_cliente, $devolucion_cliente, ["devolucion_cliente_detalles"]);

        return $devolucion_cliente;
    }

    /**
     * Eliminar devolucion_cliente
     *
     * @param DevolucionCliente $devolucion_cliente
     * @return boolean
     */
    public function eliminar(DevolucionCliente $devolucion_cliente): bool|Exception
    {
        foreach ($devolucion_cliente->devolucion_cliente_detalles as $devolucion_cliente_detalle) {
            $producto = Producto::findOrFail($devolucion_cliente_detalle->producto_id);

            // DESCONTAR STOCK SUCURSAL
            $this->kardex_producto_service->registroEgreso("DEVOLUCIÓN DE CLIENTES", $producto, $devolucion_cliente_detalle->cantidad, $producto->precio, "EGRESO POR ELIMINACIÓN DE DEVOLUCIÓN DE CLIENTES", $devolucion_cliente->sucursal_id, "DevolucionClienteDetalle", $devolucion_cliente_detalle->id);
        }

        $old_devolucion_cliente = clone $devolucion_cliente;
        $devolucion_cliente->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA DEVOLUCIÓN DE CLIENTES", $old_devolucion_cliente, null, ["devolucion_cliente_detalles"]);
        return true;
    }
}
