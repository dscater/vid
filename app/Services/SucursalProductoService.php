<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\SucursalProducto;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SucursalProductoService
{
    private $modulo = "SUCURSAL PRODUCTO";

    public function __construct(private HistorialAccionService $historialAccionService) {}
    /**
     * Lista de todos los sucursal_productos
     *
     * @return Collection
     */
    public function listado($sucursal_id = -1, bool $con_stock = false): Collection
    {
        $sucursal_productos = SucursalProducto::with(["sucursal", "producto"])->select("sucursal_productos.*");

        if (Auth::user()->sucursals_todo == 0) {
            $sucursal_productos->where("sucursal_id", Auth::user()->sucursal_id);
        }

        if ($sucursal_id != -1) {
            $sucursal_productos->where("sucursal_id", $sucursal_id);
        }

        if ($con_stock) {
            $sucursal_productos->where("stock_actual", ">", 0)->get();
        }

        $sucursal_productos = $sucursal_productos->get();
        return $sucursal_productos;
    }

    /**
     * Lista de sucursal_productos paginado con filtros
     *
     * @param integer $length
     * @param integer $page
     * @param string $search
     * @param array $columnsSerachLike
     * @param array $columnsFilter
     * @param array $columnsBetweenFilter
     * @param array $orderBy
     * @return LengthAwarePaginator
     */
    public function listadoPaginado(
        int $length,
        int $page,
        string $search = '',
        array $columnsSerachLike = [],
        array $columnsFilter = [],
        array $columnsBetweenFilter = [],
        array $orderBy = [],
        int $sucursal_id = 0
    ): LengthAwarePaginator {

        // if (Auth::user()->sucursals_todo == 0) {
        //     $sucursal_id = Auth::user()->sucursal_id;
        // }

        $sucursal_productos = Producto::leftJoin('sucursal_productos', function ($join) use ($sucursal_id) {
            $join->on('sucursal_productos.producto_id', '=', 'productos.id')
                ->where('sucursal_productos.sucursal_id', '=', $sucursal_id);
        })
            ->select(
                'productos.id',
                'productos.codigo',
                'productos.nombre',
                DB::raw('COALESCE(sucursal_productos.stock_actual, 0) as stock_actual'),
                DB::raw('COALESCE(sucursal_productos.cantidad_ideal, 0) as cantidad_ideal'),
                DB::raw('COALESCE(sucursal_productos.cantidad_minima, 0) as cantidad_minima'),
            );

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $sucursal_productos->where("sucursal_productos.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $sucursal_productos->whereBetween("sucursal_productos.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $sucursal_productos->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("productos.$col", "LIKE", "%$search%");
                }
            });
        }

        // SucursalProductoamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $sucursal_productos->orderBy($value[0], $value[1]);
            }
        }

        // Log::debug($sucursal_productos->toSql());
        $sucursal_productos = $sucursal_productos->paginate($length, ['*'], 'page', $page);

        return $sucursal_productos;
    }

    /**
     * Incrementar el stock del producto sucursal
     *
     * @param Producto $producto
     * @param float $cantidad
     * @param integer $sucursal_id
     * @return App\Models\SucursalProducto
     */
    public function incrementarStock(Producto $producto, float $cantidad, int $sucursal_id): SucursalProducto
    {
        $sucursal_producto = SucursalProducto::where("producto_id", $producto->id)
            ->where("sucursal_id", $sucursal_id)
            ->get()->first();
        if (!$sucursal_producto) {
            $sucursal_producto = $producto->sucursal_productos()->create([
                "sucursal_id" => $sucursal_id,
                "stock_actual" => $cantidad,
            ]);
        } else {
            $sucursal_producto->stock_actual = (float)$sucursal_producto->stock_actual + $cantidad;
            $sucursal_producto->save();
        }

        return $sucursal_producto;
    }

    /**
     * Decrementar el stock de un producto sucursal
     *
     * @param Producto $producto
     * @param float $cantidad
     * @param integer $sucursal_id
     * @return App\Models\SucursalProducto
     */
    public function decrementarStock(Producto $producto, float $cantidad, int $sucursal_id): SucursalProducto|null
    {
        $sucursal_producto = SucursalProducto::where("producto_id", $producto->id)
            ->where("sucursal_id", $sucursal_id)
            ->get()->first();
        if ($sucursal_producto) {
            $sucursal_producto->stock_actual = (float)$sucursal_producto->stock_actual - $cantidad;
            $sucursal_producto->save();
        }

        return $sucursal_producto;
    }

    /**
     * Verificar el stock del producto
     *
     * @param integer $producto_id
     * @param integer $sucursal_id
     * @param float $cantidad
     * @return array[bool,float]
     */
    public function verificaStockSucursalProducto(int $producto_id, int $sucursal_id, float $cantidad): array
    {
        $resultado = [false, 0];
        $sucursal_producto = SucursalProducto::where("producto_id", $producto_id)
            ->where("sucursal_id", $sucursal_id)
            ->get()->first();
        if ($sucursal_producto) {
            $stock_actual = (float)$sucursal_producto->stock_actual;
            $resultado[1] = $stock_actual;
            if ($stock_actual >= $cantidad) {
                $resultado[0] = true;
            }
        }

        return $resultado;
    }

    /**
     * Obtener un producto de una sucursal
     *
     * @param integer $producto_id
     * @param integer $sucursal_id
     * @return SucursalProducto
     */
    public function getSucursalProducto(int $producto_id, int $sucursal_id): SucursalProducto
    {
        $sucursal_producto = SucursalProducto::where("producto_id", $producto_id)
            ->where("sucursal_id", $sucursal_id)
            ->get()->first();

        if (!$sucursal_producto) {
            if ($producto_id == 0 || $sucursal_id == 0) {
                throw new Exception("Debes seleccionar la sucursal y el producto");
            }
            $sucursal_producto = SucursalProducto::create([
                "sucursal_id" => $sucursal_id,
                "producto_id" => $producto_id,
                "cantidad_ideal" => 0,
                "cantidad_minima" => 0,
                "stock_actual" => 0,
            ]);
        }

        return $sucursal_producto->load(["producto"]);
    }


    /**
     * Buscar produto en las sucursales
     *
     * @param string $search
     * @return Collection
     */
    public function buscarSucursalProductoes(string $search = "", bool $orderStock = false): Collection
    {
        $sucursal_productos = SucursalProducto::with(["producto", "sucursal"])
            ->select("sucursal_productos.*")
            ->join("productos", "productos.id", "=", "sucursal_productos.producto_id")
            ->where("stock_actual", ">", 0);

        if ($search) {
            $sucursal_productos->where("productos.nombre", "LIKE", "%$search%");
        }

        if ($orderStock) {
            $sucursal_productos->orderBy("stock_actual", "desc");
        }
        $sucursal_productos = $sucursal_productos->get();

        return $sucursal_productos;
    }

    public function actualizar(array $datos, SucursalProducto $sucursal_producto): SucursalProducto
    {
        $old_sucursal_producto = SucursalProducto::find($sucursal_producto->id);
        $sucursal_producto->update([
            "cantidad_ideal" => $datos["cantidad_ideal"],
            "cantidad_minima" => $datos["cantidad_minima"],
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN PRODUCTO DE SUCURSAL", $old_sucursal_producto, $sucursal_producto);

        return $sucursal_producto;
    }
}
