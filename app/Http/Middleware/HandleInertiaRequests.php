<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    public function rootView(Request $request)
    {
        $nom_ruta = $request->route()->getName();
        $prefix = $request->route()->getPrefix();
        $validate_prefix = ["admin", "/admin"];
        if (in_array($prefix, $validate_prefix) || in_array($nom_ruta, ['login', 'registro'])) {
            return 'app';
        }
        return 'portal';
    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? $request->user()->load(["inscripcion.postulante", "role"]) : null,
                'permisos' => $request->user() ? $request->user()->permisos : null,
                "requisito" => $request->user() && $request->user()->inscripcion ? $request->user()->inscripcion->requisito : null,
            ],
            'url_assets' => asset(''),
            'url_principal' => url(''),
            'flash' => [
                'bien' => fn() => $request->session()->get('bien'),
                'error' => fn() => $request->session()->get('error'),
                'planilla' => fn() => $request->session()->get('planilla'),
                'codigoInsc' => fn() => $request->session()->get('codigoInsc'),
            ],
            'codigoInsc' => session('codigoInsc')
        ];
    }
}
