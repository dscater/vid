<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoriaStoreRequest;
use App\Http\Requests\SubCategoriaUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\SubCategoria;
use App\Models\User;
use App\Services\SubCategoriaService;
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

class SubCategoriaController extends Controller
{
    public function __construct(private SubCategoriaService $sub_categoriaService) {}

    /**
     * Listado de sub_categorias sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "sub_categorias" => $this->sub_categoriaService->listado()
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

        $sub_categorias = $this->sub_categoriaService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $sub_categorias->items(),
            "total" => $sub_categorias->total(),
            "lastPage" => $sub_categorias->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de sub_categorias paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo sub_categoria
     *
     * @param SubCategoriaStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(SubCategoriaStoreRequest $request): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el SubCategoria
            $this->sub_categoriaService->crear($request->validated());
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
     * Mostrar un sub_categoria
     *
     * @param SubCategoria $sub_categoria
     * @return JsonResponse
     */
    public function show(SubCategoria $sub_categoria): JsonResponse
    {
        return response()->JSON($sub_categoria);
    }
    public function actualizaPermiso(SubCategoria $sub_categoria, Request $request)
    {
        $sw_cambio = $request->sw_cambio;
        $modulo = $request->modulo;
        $accion = $request->accion;
        $o_modulo = Modulo::where("modulo", $modulo)->where("accion", $accion)->get()->first();
        $permiso = Permiso::where("sub_categoria_id", $sub_categoria->id)
            ->where("modulo_id", $o_modulo->id)
            ->get()->first();
        if ($sw_cambio == 1) {
            if (!$permiso) {
                $sub_categoria->o_permisos()->create([
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
            ->where("sub_categoria_id", $sub_categoria->id)
            ->where("modulos.modulo", $o_modulo->modulo)->get();

        return response()->JSON([
            "array_permisos" => $array_permisos
        ]);
    }

    public function update(SubCategoria $sub_categoria, SubCategoriaUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar sub_categoria
            $this->sub_categoriaService->actualizar($request->validated(), $sub_categoria);
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
     * Eliminar sub_categoria
     *
     * @param SubCategoria $sub_categoria
     * @return JsonResponse|Response
     */
    public function destroy(SubCategoria $sub_categoria): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->sub_categoriaService->eliminar($sub_categoria);
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
