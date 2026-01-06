<?php

namespace App\Http\Controllers;

use App\Http\Requests\AjusteStoreRequest;
use App\Http\Requests\AjusteUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Ajuste;
use App\Models\User;
use App\Services\AjusteService;
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

class AjusteController extends Controller
{
    public function __construct(private AjusteService $ajusteService) {}

    /**
     * Listado de ajustes sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "ajustes" => $this->ajusteService->listado()
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
            "motivo"
        ];
        $columnsFilter = [];
        $columnsBetweenFilter = [];
        $arrayOrderBy = [];
        if ($orderByCol && $desc) {
            $arrayOrderBy = [
                [$orderByCol, $desc]
            ];
        }

        $ajustes = $this->ajusteService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $ajustes->items(),
            "total" => $ajustes->total(),
            "lastPage" => $ajustes->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de ajustes paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo ajuste
     *
     * @param AjusteStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(AjusteStoreRequest $request): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el Ajuste
            $this->ajusteService->crear($request->validated());
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
     * Mostrar un ajuste
     *
     * @param Ajuste $ajuste
     * @return JsonResponse
     */
    public function show(Ajuste $ajuste): JsonResponse
    {
        return response()->JSON($ajuste);
    }

    public function update(Ajuste $ajuste, AjusteUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar ajuste
            $this->ajusteService->actualizar($request->validated(), $ajuste);
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
     * Eliminar ajuste
     *
     * @param Ajuste $ajuste
     * @return JsonResponse|Response
     */
    public function destroy(Ajuste $ajuste): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->ajusteService->eliminar($ajuste);
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
