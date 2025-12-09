<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProformaAprobarRequest;
use App\Http\Requests\ProformaStoreRequest;
use App\Http\Requests\ProformaUpdateRequest;
use App\Models\Proforma;
use App\Services\ProformaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use App\library\numero_a_letras\src\NumeroALetras;

class ProformaController extends Controller
{
    public function __construct(private ProformaService $proformaService) {}

    /**
     * Listado de proformas sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "proformas" => $this->proformaService->listado()
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
        Log::debug($arrayOrderBy);

        $proformas = $this->proformaService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $proformas->items(),
            "total" => $proformas->total(),
            "lastPage" => $proformas->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de proformas paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo proforma
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request): Response|JsonResponse
    {
        try {

            return Cache::lock("ordenSalidaStore")->block(10, function () use ($request) {
                $request = app(ProformaStoreRequest::class);
                DB::beginTransaction();
                try {
                    $this->proformaService->crear($request->validated());
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
     * Mostrar un proforma
     *
     * @param Proforma $proforma
     * @return JsonResponse
     */
    public function show(Proforma $proforma): JsonResponse
    {
        $convertir = new NumeroALetras();
        $array_monto = explode('.', $proforma->total_f);
        $literal = $convertir->convertir($array_monto[0]);
        $literal .= " " . $array_monto[1];
        $literal = strtolower($literal);
        $literal = ucfirst($literal) . "/100." . " Bolivianos";;

        return response()->JSON([
            "proforma" => $proforma->load(["proforma_detalles.producto:id,nombre", "proforma_detalles.unidad_medida:id,nombre", "cliente:id,razon_social,nit", "user:id,nombre,paterno,materno"]),
            "literal" => $literal
        ]);
    }

    public function update(Proforma $proforma, ProformaUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar proforma
            $this->proformaService->actualizar($request->validated(), $proforma);
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

    public function aprobar(Proforma $proforma, ProformaAprobarRequest $request)
    {
        DB::beginTransaction();
        try {
            // aprobar proforma
            $proforma = $this->proformaService->aprobar($request->validated(), $proforma);
            DB::commit();
            return response()->JSON([
                "sw" => true,
                "proforma" => $proforma,
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
     * Eliminar proforma
     *
     * @param Proforma $proforma
     * @return JsonResponse|Response
     */
    public function destroy(Proforma $proforma): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->proformaService->eliminar($proforma);
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
