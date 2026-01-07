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

class FinStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fin-stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra el stock final de los productos de cada sucursal';

    public function __construct(
        private ParametroSucursalService $parametroSucursalService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Log::info("SE EJECUTO FINAL DE STOCKS");
        $parametro = $this->parametroSucursalService->getParametro();
        $horaFinal = Carbon::createFromFormat('H:i:s', $parametro->valor2);
        $fechaHoy  = Carbon::today();
        if (! now()->isSameMinute($horaFinal)) {
            // no ejecutar nada si no es la hora
            return Command::SUCCESS;
        }

        if (Cache::has("fin-stocks-$fechaHoy")) {
            return Command::SUCCESS;
        }

        Cache::put("fin-stocks-$fechaHoy", true, now()->endOfDay());

        DB::transaction(function () use ($horaFinal, $fechaHoy) {

            $sucursales = Sucursal::where('estado', 1)->get();

            foreach ($sucursales as $sucursal) {

                // Stocks actuales de la sucursal
                $stocks = SucursalProducto::where('sucursal_id', $sucursal->id)
                    ->get();

                foreach ($stocks as $stock) {

                    // Movimiento del día (debe existir)
                    $movimiento = MovimientoHora::whereDate('fecha', $fechaHoy)
                        ->where('sucursal_id', $sucursal->id)
                        ->where('producto_id', $stock->producto_id)
                        ->first();

                    if (! $movimiento) {
                        Log::warning('MovimientoHora no encontrado para cierre', [
                            'fecha'        => $fechaHoy->toDateString(),
                            'sucursal_id'  => $sucursal->id,
                            'producto_id'  => $stock->producto_id,
                        ]);
                        continue;
                    }

                    // Actualizar stock final
                    $movimiento->update([
                        'cantidad_final' => $stock->stock_actual,
                        'hora_final'     => $horaFinal->format('H:i'),
                    ]);
                }
            }
        });

        $this->info('Stock final registrado correctamente');

        return Command::SUCCESS;
    }
}
