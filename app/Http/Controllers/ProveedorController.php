<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedorStoreRequest;
use App\Http\Requests\ProveedorUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Proveedor;
use App\Models\User;
use App\Services\ProveedorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as ResponseInertia;

class ProveedorController extends Controller
{
    public function __construct(private ProveedorService $proveedorService) {}

    /**
     * Listado de proveedors sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "proveedors" => $this->proveedorService->listado()
        ]);
    }

    public function paginado(Request $request)
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

        $proveedors = $this->proveedorService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $proveedors->items(),
            "total" => $proveedors->total(),
            "lastPage" => $proveedors->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de proveedors paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo proveedor
     *
     * @param ProveedorStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(ProveedorStoreRequest $request): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el Proveedor
            $this->proveedorService->crear($request->validated());
            DB::commit();
            return response()->JSON([
                "sw" => true,
                "message" => "Proceso realizado con éxito"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Mostrar un proveedor
     *
     * @param Proveedor $proveedor
     * @return JsonResponse
     */
    public function show(Proveedor $proveedor): JsonResponse
    {
        return response()->JSON($proveedor);
    }
    public function actualizaPermiso(Proveedor $proveedor, Request $request)
    {
        $sw_cambio = $request->sw_cambio;
        $modulo = $request->modulo;
        $accion = $request->accion;
        $o_modulo = Modulo::where("modulo", $modulo)->where("accion", $accion)->get()->first();
        $permiso = Permiso::where("proveedor_id", $proveedor->id)
            ->where("modulo_id", $o_modulo->id)
            ->get()->first();
        if ($sw_cambio == 1) {
            if (!$permiso) {
                $proveedor->o_permisos()->create([
                    "modulo_id" => $o_modulo->id
                ]);
            }
        } else {
            if ($permiso) {
                $permiso->delete();
            }
        }

        $array_permisos = Permiso::select("modulos.nombre", "modulos.accion")
            ->join("modulos", "modulos.id", "=", "permisos.modulo_id")
            ->where("proveedor_id", $proveedor->id)
            ->where("modulos.modulo", $o_modulo->modulo)->get();

        return response()->JSON([
            "array_permisos" => $array_permisos
        ]);
    }

    public function update(Proveedor $proveedor, ProveedorUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar proveedor
            $this->proveedorService->actualizar($request->validated(), $proveedor);
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

    /**
     * Eliminar proveedor
     *
     * @param Proveedor $proveedor
     * @return JsonResponse|Response
     */
    public function destroy(Proveedor $proveedor): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->proveedorService->eliminar($proveedor);
            DB::commit();
            return response()->JSON([
                "sw" => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
