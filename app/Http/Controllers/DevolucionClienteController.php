<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevolucionClienteAprobarRequest;
use App\Http\Requests\DevolucionClienteStoreRequest;
use App\Http\Requests\DevolucionClienteUpdateRequest;
use App\Models\DevolucionCliente;
use App\Services\DevolucionClienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class DevolucionClienteController extends Controller
{
    public function __construct(private DevolucionClienteService $devolucion_clienteService) {}

    /**
     * Listado de devolucion_clientes sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "devolucion_clientes" => $this->devolucion_clienteService->listado()
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

        $devolucion_clientes = $this->devolucion_clienteService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $devolucion_clientes->items(),
            "total" => $devolucion_clientes->total(),
            "lastPage" => $devolucion_clientes->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de devolucion_clientes paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo devolucion_cliente
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request): Response|JsonResponse
    {
        try {

            return Cache::lock("devolucionStore")->block(10, function () use ($request) {
                $request = app(DevolucionClienteStoreRequest::class);
                DB::beginTransaction();
                try {
                    $this->devolucion_clienteService->crear($request->validated());
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
     * Mostrar un devolucion_cliente
     *
     * @param DevolucionCliente $devolucion_cliente
     * @return JsonResponse
     */
    public function show(DevolucionCliente $devolucion_cliente): JsonResponse
    {
        return response()->JSON([
            "devolucion_cliente" => $devolucion_cliente->load(["devolucion_cliente_detalles.producto"]),
        ]);
    }

    public function update(DevolucionCliente $devolucion_cliente, DevolucionClienteUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar devolucion_cliente
            $this->devolucion_clienteService->actualizar($request->validated(), $devolucion_cliente);
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

    public function aprobar(DevolucionCliente $devolucion_cliente, DevolucionClienteAprobarRequest $request)
    {
        DB::beginTransaction();
        try {
            // aprobar devolucion_cliente
            $this->devolucion_clienteService->aprobar($request->validated(), $devolucion_cliente);
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
     * Eliminar devolucion_cliente
     *
     * @param DevolucionCliente $devolucion_cliente
     * @return JsonResponse|Response
     */
    public function destroy(DevolucionCliente $devolucion_cliente): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->devolucion_clienteService->eliminar($devolucion_cliente);
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
