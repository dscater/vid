<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferenciaAprobarRequest;
use App\Http\Requests\TransferenciaStoreRequest;
use App\Http\Requests\TransferenciaUpdateRequest;
use App\Models\Transferencia;
use App\Services\TransferenciaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class TransferenciaController extends Controller
{
    public function __construct(private TransferenciaService $transferenciaService) {}

    /**
     * Listado de transferencias sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "transferencias" => $this->transferenciaService->listado()
        ]);
    }

    public function paginado(Request $request)
    {
        $perPage = $request->perPage;
        $page = (int)($request->input("page", 1));
        $search = (string)$request->input("search", "");
        $orderByCol = $request->orderBy;
        $desc = $request->orderAsc;

        $columnsSerachLike = [
            "codigo"
        ];

        $realacionSearch = [
            "sucursal" => "nombre",
            "sucursalDestino" => "nombre",
        ];
        $columnsFilter = [];
        $columnsBetweenFilter = [];
        $arrayOrderBy = [];
        if ($orderByCol && $desc) {
            $arrayOrderBy = [
                [$orderByCol, $desc]
            ];
        }

        $transferencias = $this->transferenciaService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy, $realacionSearch);
        return response()->JSON([
            "data" => $transferencias->items(),
            "total" => $transferencias->total(),
            "lastPage" => $transferencias->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de transferencias paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo transferencia
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request): Response|JsonResponse
    {
        try {

            return Cache::lock("transferenciaStore")->block(10, function () use ($request) {
                $request = app(TransferenciaStoreRequest::class);
                DB::beginTransaction();
                try {
                    $this->transferenciaService->crear($request->validated());
                    DB::commit();
                    return response()->JSON([
                        "sw" => true,
                        "message" => "Proceso realizado con éxito"
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw ValidationException::withMessages([
                        'error' =>  $e->getMessage()
                    ]);
                }
            });
        } catch (ValidationException $ve) {
            // Si falla la validación fuera del lock
            throw ValidationException::withMessages($ve->errors());
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage()
            ]);
        }
    }

    /**
     * Mostrar un transferencia
     *
     * @param Transferencia $transferencia
     * @return JsonResponse
     */
    public function show(Transferencia $transferencia): JsonResponse
    {
        return response()->JSON([
            "transferencia" => $transferencia->load(["transferencia_detalles.producto", "transferencia_detalles.oSucursalAjuste", "sucursal", "sucursalDestino", "user_solicitante", "user_aprobo"]),
        ]);
    }

    public function update(Transferencia $transferencia, TransferenciaUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar transferencia
            $this->transferenciaService->actualizar($request->validated(), $transferencia);
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

    public function aprobar(Transferencia $transferencia, TransferenciaAprobarRequest $request)
    {
        DB::beginTransaction();
        try {
            // aprobar transferencia
            $this->transferenciaService->aprobar($request->validated(), $transferencia);
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
     * Eliminar transferencia
     *
     * @param Transferencia $transferencia
     * @return JsonResponse|Response
     */
    public function destroy(Transferencia $transferencia): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->transferenciaService->eliminar($transferencia);
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
