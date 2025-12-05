<?php

namespace App\Http\Controllers;

use App\Http\Requests\SucursalProductoUpdateRequest;
use App\Models\SucursalProducto;
use App\Services\SucursalProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Inertia\Response as InertiaResponse;
use Inertia\Inertia;

class SucursalProductoController extends Controller
{
    public function __construct(private SucursalProductoService $sucursalProductoService) {}

    /**
     * Lista de registros
     *
     * @return JsonResponse
     */
    public function listado(Request $request): JsonResponse
    {
        return response()->JSON([
            "sucursal_productos" => $this->sucursalProductoService->listado($request->sucursal_id ?? 0, $request->con_stock ?? false)
        ]);
    }

    /**
     * Endpoint para obtener la lista de sucursal_productos paginado para data table
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function paginado(Request $request): JsonResponse
    {
        $perPage = $request->perPage;
        $page = (int)($request->input("page", 1));
        $search = (string)$request->input("search", "");
        $orderByCol = $request->orderByCol;
        $desc = $request->desc;

        $columnsSerachLike = [
            "descripcion"
        ];
        $columnsFilter = [];
        $columnsBetweenFilter = [];
        $arrayOrderBy = [];
        if ($orderByCol && $desc) {
            $arrayOrderBy = [
                [$orderByCol, $desc]
            ];
        }

        if (isset($request->sucursal_id) && trim($request->sucursal_id) != "") {
            $sucursals = $this->sucursalProductoService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy, (int)$request->sucursal_id);
            return response()->JSON([
                "data" => $sucursals->items(),
                "total" => $sucursals->total(),
                "lastPage" => $sucursals->lastPage()
            ]);
        } else {
            return response()->JSON([
                "data" => [],
                "total" => 0,
                "lastPage" => 0
            ]);
        }
    }


    /**
     * Buscar un solo producto por Sucursal
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function getSucursalProducto(Request $request): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $sucursal_producto = $this->sucursalProductoService->getSucursalProducto((int)$request["producto_id"], (int)$request["sucursal_id"]);
            DB::commit();
            return response()->JSON([
                "sucursal_producto" => $sucursal_producto
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Buscar un producto por sucursales
     *
     * @return void
     */
    public function getSucursalProductoes(Request $request)
    {
        $search = $request->input("search", "");

        return response()->JSON([
            "sucursal_productos" => trim($search) ? $this->sucursalProductoService->buscarSucursalProductoes($search ?? "", true) : []
        ]);
    }

    public function update(SucursalProducto $sucursal_producto, SucursalProductoUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar sucursal producto
            $this->sucursalProductoService->actualizar($request->validated(), $sucursal_producto);
            DB::commit();
            return response()->JSON([
                "sw" => true,
                "message" => "Proceso realizado con éxito"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
