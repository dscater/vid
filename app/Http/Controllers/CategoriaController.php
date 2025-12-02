<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaStoreRequest;
use App\Http\Requests\CategoriaUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Categoria;
use App\Models\User;
use App\Services\CategoriaService;
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

class CategoriaController extends Controller
{
    public function __construct(private CategoriaService $categoriaService) {}

    /**
     * Listado de categorias sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "categorias" => $this->categoriaService->listado()
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

        $categorias = $this->categoriaService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $categorias->items(),
            "total" => $categorias->total(),
            "lastPage" => $categorias->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de categorias paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo categoria
     *
     * @param CategoriaStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(CategoriaStoreRequest $request): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el Categoria
            $this->categoriaService->crear($request->validated());
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
     * Mostrar un categoria
     *
     * @param Categoria $categoria
     * @return JsonResponse
     */
    public function show(Categoria $categoria): JsonResponse
    {
        return response()->JSON($categoria);
    }
    public function actualizaPermiso(Categoria $categoria, Request $request)
    {
        $sw_cambio = $request->sw_cambio;
        $modulo = $request->modulo;
        $accion = $request->accion;
        $o_modulo = Modulo::where("modulo", $modulo)->where("accion", $accion)->get()->first();
        $permiso = Permiso::where("categoria_id", $categoria->id)
            ->where("modulo_id", $o_modulo->id)
            ->get()->first();
        if ($sw_cambio == 1) {
            if (!$permiso) {
                $categoria->o_permisos()->create([
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
            ->where("categoria_id", $categoria->id)
            ->where("modulos.modulo", $o_modulo->modulo)->get();

        return response()->JSON([
            "array_permisos" => $array_permisos
        ]);
    }

    public function update(Categoria $categoria, CategoriaUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar categoria
            $this->categoriaService->actualizar($request->validated(), $categoria);
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
     * Eliminar categoria
     *
     * @param Categoria $categoria
     * @return JsonResponse|Response
     */
    public function destroy(Categoria $categoria): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->categoriaService->eliminar($categoria);
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
