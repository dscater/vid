<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnidadMedidaStoreRequest;
use App\Http\Requests\UnidadMedidaUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\UnidadMedida;
use App\Models\User;
use App\Services\UnidadMedidaService;
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

class UnidadMedidaController extends Controller
{
    public function __construct(private UnidadMedidaService $unidad_medidaService) {}

    /**
     * Listado de unidad_medidas sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "unidad_medidas" => $this->unidad_medidaService->listado()
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

        $unidad_medidas = $this->unidad_medidaService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $unidad_medidas->items(),
            "total" => $unidad_medidas->total(),
            "lastPage" => $unidad_medidas->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de unidad_medidas paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo unidad_medida
     *
     * @param UnidadMedidaStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(UnidadMedidaStoreRequest $request): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el UnidadMedida
            $this->unidad_medidaService->crear($request->validated());
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
     * Mostrar un unidad_medida
     *
     * @param UnidadMedida $unidad_medida
     * @return JsonResponse
     */
    public function show(UnidadMedida $unidad_medida): JsonResponse
    {
        return response()->JSON($unidad_medida);
    }
    public function actualizaPermiso(UnidadMedida $unidad_medida, Request $request)
    {
        $sw_cambio = $request->sw_cambio;
        $modulo = $request->modulo;
        $accion = $request->accion;
        $o_modulo = Modulo::where("modulo", $modulo)->where("accion", $accion)->get()->first();
        $permiso = Permiso::where("unidad_medida_id", $unidad_medida->id)
            ->where("modulo_id", $o_modulo->id)
            ->get()->first();
        if ($sw_cambio == 1) {
            if (!$permiso) {
                $unidad_medida->o_permisos()->create([
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
            ->where("unidad_medida_id", $unidad_medida->id)
            ->where("modulos.modulo", $o_modulo->modulo)->get();

        return response()->JSON([
            "array_permisos" => $array_permisos
        ]);
    }

    public function update(UnidadMedida $unidad_medida, UnidadMedidaUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar unidad_medida
            $this->unidad_medidaService->actualizar($request->validated(), $unidad_medida);
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
     * Eliminar unidad_medida
     *
     * @param UnidadMedida $unidad_medida
     * @return JsonResponse|Response
     */
    public function destroy(UnidadMedida $unidad_medida): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->unidad_medidaService->eliminar($unidad_medida);
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
