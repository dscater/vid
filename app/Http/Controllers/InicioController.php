<?php

namespace App\Http\Controllers;

use App\Models\DescargaDocumento;
use App\Models\HistorialAccion;
use App\Models\Inscripcion;
use App\Models\Parametrizacion;
use App\Services\HistorialAccionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class InicioController extends Controller
{

    public function verificaLogin()
    {
        $sw = false;
        if (Auth::check()) {
            $sw = true;
        }

        return response()->JSON(["sw" => $sw]);
    }

    public function inicio()
    {
        $array_infos = UserController::getInfoBoxUser();
        return response()->JSON([
            "array_infos" => $array_infos,
        ]);
    }

    public function login()
    {
        return Inertia::render("Auth/Login");
    }
}
