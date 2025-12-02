<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Producto;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class ProductoService
{

    private $modulo = "PRODUCTOS";

    public function __construct(private  CargarArchivoService $cargarArchivoService, private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $productos = Producto::select("productos.*")->get();
        return $productos;
    }
    /**
     * Lista de productos paginado con filtros
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
        $productos = Producto::select("productos.*")
            ->with(["categoria:id,nombre"])
            ->with(["marca:id,nombre"])
            ->with(["unidad_medida:id,nombre"]);

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $productos->where("productos.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $productos->whereBetween("productos.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $productos->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $productos->orderBy($value[0], $value[1]);
            }
        }


        $productos = $productos->paginate($length, ['*'], 'page', $page);
        return $productos;
    }

    /**
     * Crear producto
     *
     * @param array $datos
     * @return Producto
     */
    public function crear(array $datos): Producto
    {

        $producto = Producto::create([
            "codigo" => mb_strtoupper($datos["codigo"]),
            "nombre" => mb_strtoupper($datos["nombre"]),
            "unidades_caja" => $datos["unidades_caja"],
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "categoria_id" => $datos["categoria_id"],
            "marca_id" => $datos["marca_id"],
            "precio" => $datos["precio"],
            // "precio_ppp" => $datos["precio_ppp"],
            // "ppp" => $datos["ppp"],
            "unidad_medida_id" => $datos["unidad_medida_id"],
            "estado" => $datos["estado"],
        ]);

        // cargar imagen
        if (isset($datos["imagen"]) && !is_string($datos["imagen"])) {
            $this->cargarImagen($producto, $datos["imagen"]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN PRODUCTO", $producto);

        return $producto;
    }

    /**
     * Cargar imagen
     *
     * @param Producto $producto
     * @param UploadedFile $imagen
     * @return void
     */
    public function cargarImagen(Producto $producto, UploadedFile $imagen): void
    {
        if ($producto->imagen) {
            \File::delete(public_path("imgs/productos/" . $producto->imagen));
        }

        $nombre = $producto->id . time();
        $producto->imagen = $this->cargarArchivoService->cargarArchivo($imagen, public_path("imgs/productos"), $nombre);
        $producto->save();
    }

    /**
     * Actualizar producto
     *
     * @param array $datos
     * @param Producto $producto
     * @return Producto
     */
    public function actualizar(array $datos, Producto $producto): Producto
    {
        $old_producto = Producto::find($producto->id);
        $producto->update([
            "codigo" => mb_strtoupper($datos["codigo"]),
            "nombre" => mb_strtoupper($datos["nombre"]),
            "unidades_caja" => $datos["unidades_caja"],
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "categoria_id" => $datos["categoria_id"],
            "marca_id" => $datos["marca_id"],
            "precio" => $datos["precio"],
            // "precio_ppp" => $datos["precio_ppp"],
            // "ppp" => $datos["ppp"],
            "unidad_medida_id" => $datos["unidad_medida_id"],
            "estado" => $datos["estado"],
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN PRODUCTO", $old_producto, $producto);

        return $producto;
    }

    /**
     * Eliminar producto
     *
     * @param Producto $producto
     * @return boolean
     */
    public function eliminar(Producto $producto): bool|Exception
    {
        $old_producto = clone $producto;
        $producto->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN PRODUCTO", $old_producto);

        return true;
    }
}
