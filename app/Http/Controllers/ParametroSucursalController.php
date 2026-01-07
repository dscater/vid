<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParametroSucursalUpdateStore;
use App\Services\ParametroSucursalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ParametroSucursalController extends Controller
{
    public function __construct(private ParametroSucursalService $ParametroSucursal_service) {}

    public function index()
    {
        return response()->JSON(
            $this->ParametroSucursal_service->getParametro()
        );
    }

    public function store(ParametroSucursalUpdateStore $request)
    {
        DB::beginTransaction();
        try {
            // actualizar parametro cliente
            $this->ParametroSucursal_service->actualizar($request->validated());
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
