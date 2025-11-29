<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Publicacion;
use App\Models\User;
use App\Services\EnviarCorreoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return "backend";
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        if ($request->ajax()) {
            $user = User::findOrFail(Auth::user()->id);
            $tipo = $user->tipo;
            // if ($tipo == 'POSTULANTE') {
            //     Auth::logout();
            //     // GENERAR CÓDIGO DE VERIFICACIÓN DE 4 DIGITOS
            //     $codigo = random_int(1000, 9999);
            //     $user->codigo = $codigo;
            //     $user->save();
            //     $enviarCorreoService = new EnviarCorreoService();
            //     $enviarCorreoService->mailCodigoVerificacion($user, $codigo);

            //     return response()->JSON(["user" => $user, "codigo" => true]);
            // }

            return response()->JSON(["user" => Auth::user(), "codigo" => false]);
        }

        return redirect()->intended(route('inicio'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->ajax()) {
            return response()->JSON(true);
        }
        return redirect()->route('portal.index');
    }
}
