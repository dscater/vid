<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Cliente;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ClienteService
{

    private $modulo = "CLIENTES";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $clientes = Cliente::select("clientes.*")->get();
        return $clientes;
    }
    /**
     * Lista de clientes paginado con filtros
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
        $clientes = Cliente::select("clientes.*");

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $clientes->where("clientes.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $clientes->whereBetween("clientes.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $clientes->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $clientes->orderBy($value[0], $value[1]);
            }
        }


        $clientes = $clientes->paginate($length, ['*'], 'page', $page);
        return $clientes;
    }

    /**
     * Crear cliente
     *
     * @param array $datos
     * @return Cliente
     */
    public function crear(array $datos): Cliente
    {

        $cliente = Cliente::create([
            "razon_social" => mb_strtoupper($datos["razon_social"]),
            "tipo" => mb_strtoupper($datos["tipo"]),
            "nit" => mb_strtoupper($datos["nit"]),
            "nombre_punto" => mb_strtoupper($datos["nombre_punto"]),
            "nombre_prop" => mb_strtoupper($datos["nombre_prop"]),
            "ci_prop" => mb_strtoupper($datos["ci_prop"]),
            "correo" => $datos["correo"],
            "cel" => mb_strtoupper($datos["cel"]),
            "fono" => mb_strtoupper($datos["fono"]),
            "dir" => mb_strtoupper($datos["dir"]),
            "latitud" => $datos["latitud"],
            "longitud" => $datos["longitud"],
            "ciudad" => mb_strtoupper($datos["ciudad"]),
            "contactos" => array_map(function ($contacto) {
                return array_map(function ($valor) {
                    return is_string($valor) ? mb_strtoupper($valor) : $valor;
                }, $contacto);
            }, $datos["contactos"]),
            "estado" => mb_strtoupper($datos["estado"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN CLIENTE", $cliente);

        return $cliente;
    }

    /**
     * Actualizar cliente
     *
     * @param array $datos
     * @param Cliente $cliente
     * @return Cliente
     */
    public function actualizar(array $datos, Cliente $cliente): Cliente
    {
        $old_cliente = Cliente::find($cliente->id);
        $cliente->update([
            "razon_social" => mb_strtoupper($datos["razon_social"]),
            "tipo" => mb_strtoupper($datos["tipo"]),
            "nit" => mb_strtoupper($datos["nit"]),
            "nombre_punto" => mb_strtoupper($datos["nombre_punto"]),
            "nombre_prop" => mb_strtoupper($datos["nombre_prop"]),
            "ci_prop" => mb_strtoupper($datos["ci_prop"]),
            "correo" => $datos["correo"],
            "cel" => mb_strtoupper($datos["cel"]),
            "fono" => mb_strtoupper($datos["fono"]),
            "dir" => mb_strtoupper($datos["dir"]),
            "latitud" => $datos["latitud"],
            "longitud" => $datos["longitud"],
            "ciudad" => mb_strtoupper($datos["ciudad"]),
            "contactos" => array_map(function ($contacto) {
                return array_map(function ($valor) {
                    return is_string($valor) ? mb_strtoupper($valor) : $valor;
                }, $contacto);
            }, $datos["contactos"]),
            "estado" => mb_strtoupper($datos["estado"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN CLIENTE", $old_cliente, $cliente);

        return $cliente;
    }

    /**
     * Eliminar cliente
     *
     * @param Cliente $cliente
     * @return boolean
     */
    public function eliminar(Cliente $cliente): bool|Exception
    {
        $old_cliente = clone $cliente;
        $cliente->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN CLIENTE", $old_cliente);

        return true;
    }
}
