<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParametroClienteUpdateStore;
use App\Services\ParametroClienteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ParametroClienteController extends Controller
{
    public function __construct(private ParametroClienteService $parametro_cliente_service) {}

    public function index()
    {
        return response()->JSON(
            $this->parametro_cliente_service->getParametro()
        );
    }

    public function store(ParametroClienteUpdateStore $request)
    {
        DB::beginTransaction();
        try {
            // actualizar parametro cliente
            $this->parametro_cliente_service->actualizar($request->validated());
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

    public function recalcularRanking()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $this->parametro_cliente_service->asignarRank();
        return response()->JSON(true);
    }
}
