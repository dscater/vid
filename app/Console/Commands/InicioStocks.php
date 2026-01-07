<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sucursal;
use App\Models\Producto;
use App\Models\MovimientoHora;
use App\Models\SucursalProducto;
use App\Services\ParametroSucursalService;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InicioStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inicio-stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra el stock inicial de los productos de cada sucursal';


    public function __construct(
        private ParametroSucursalService $parametroSucursalService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("SE EJECUTO INICIO DE STOCKS");
        $parametro = $this->parametroSucursalService->getParametro();
        $horaInicial = Carbon::createFromFormat('H:i:s', $parametro->valor1);
        $fechaHoy    = Carbon::today();

        if (! now()->isSameMinute($horaInicial)) {
            // no ejecutar nada si no es la hora
            return Command::SUCCESS;
        }
        $ahora = now();
        $fechaHoy = $ahora->toDateString();
        // Ya se ejecutó hoy
        if (Cache::has("inicio-stocks-$fechaHoy")) {
            return Command::SUCCESS;
        }

        Cache::put("inicio-stocks-$fechaHoy", true, now()->endOfDay());


        DB::transaction(function () use ($horaInicial, $fechaHoy) {

            $sucursales = Sucursal::where('estado', 1)->get();
            $productos  = Producto::where('estado', 1)->get();

            foreach ($sucursales as $sucursal) {
                foreach ($productos as $producto) {

                    // Evitar duplicado diario
                    $existe = MovimientoHora::whereDate('fecha', $fechaHoy)
                        ->where('sucursal_id', $sucursal->id)
                        ->where('producto_id', $producto->id)
                        ->exists();

                    if ($existe) {
                        continue;
                    }

                    // Último movimiento anterior
                    $ultimoMovimiento = MovimientoHora::where('sucursal_id', $sucursal->id)
                        ->where('producto_id', $producto->id)
                        ->whereNotNull('cantidad_final')
                        ->orderByDesc('fecha')
                        ->orderByDesc('hora_final')
                        ->first();

                    if ($ultimoMovimiento) {
                        $cantidadInicial = $ultimoMovimiento
                            ? $ultimoMovimiento->cantidad_final
                            : 0;
                    } else {
                        // buscar el stock del producto sucursal
                        $sucursalProducto = SucursalProducto::where("sucursal_id", $sucursal->id)
                            ->where("producto_id", $producto->id)
                            ->get()->first();
                        $cantidadInicial = $sucursalProducto
                            ? $sucursalProducto->stock_actual
                            : 0;
                    }

                    MovimientoHora::create([
                        'sucursal_id'      => $sucursal->id,
                        'producto_id'      => $producto->id,
                        'fecha'            => $fechaHoy,
                        'hora_inicial'     => $horaInicial->format('H:i'),
                        'cantidad_inicial' => $cantidadInicial,
                    ]);
                }
            }
        });

        $this->info('Stock inicial registrado correctamente');
        return Command::SUCCESS;
    }
}
