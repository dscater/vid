    @inject('configuracion', 'App\Models\Configuracion')
    @inject('parametroSucursal', 'App\Models\ParametroSucursal')
    @php
        $horaInicial = date('H:i', strtotime($parametroSucursal->first()->valor1)) ?? '08:00';
        $horaFinal = date('H:i', strtotime($parametroSucursal->first()->valor2)) ?? '20:00';
        $sucursal = $sucursals[0];
    @endphp
    <div class="encabezado">
        <h2 class="titulo">
            {{ $configuracion->first()->nombre_sistema }}
        </h2>
        <h4 class="texto">MOVIMIENTOS DE INVENTARIO</h4>
        <h4 class="texto">{{ $sucursal->nombre }}</h4>
        <h4 class="fecha">Del {{ date('d/m/Y', strtotime($fecha_ini)) }} al {{ date('d/m/Y', strtotime($fecha_fin)) }}
        </h4>
    </div>
    @php
        // INGRESOS
        $ingresos = [];
        if ($sucursal->almacen == 1) {
            // CENTRAL
            $ingresos = App\Models\SolicitudIngreso::whereBetween('fecha_ingreso', [$fecha_ini, $fecha_fin])
                ->whereIn('verificado', [1, 2, 3])
                ->get();
        }
        if ($sucursal->almacen == 2) {
            // AJUSTE
            $ingresos = App\Models\Ajuste::whereBetween('fecha', [$fecha_ini, $fecha_fin])->get();
        }
        if ($sucursal->almacen == 0) {
            // NORMAL
            $ingresos = App\Models\OrdenSalida::whereBetween('fecha', [$fecha_ini, $fecha_fin])
                ->where('sucursal_id', $sucursal->id)
                ->get();
        }
        // SALIDAS
        $salidas = [];
        if ($sucursal->almacen == 1) {
            // CENTRAL
            $salidas = App\Models\OrdenSalida::whereBetween('fecha', [$fecha_ini, $fecha_fin])
                ->whereIn('verificado', [1, 2])
                ->get();
        }
        if ($sucursal->almacen == 2) {
            // AJUSTE
            $salidas = App\Models\AjusteReposicion::whereBetween('fecha', [$fecha_ini, $fecha_fin])->get();
        }
        if ($sucursal->almacen == 0) {
            // NORMAL
            $salidas = App\Models\OrdenVenta::whereBetween('fecha', [$fecha_ini, $fecha_fin])
                ->where('sucursal_id', $sucursal->id)
                ->where('verificado', 2)
                ->get();
        }

    @endphp
    <table border="1">
        <thead>
            <tr>
                <th width="7%">PRODUCTO</th>
                <th>UNIDAD DE MEDIDA</th>
                <th class="vertical bg2">
                    <div>
                        STOCK INICIAL<br />
                        {{ $horaInicial }}
                    </div>
                </th>
                @foreach ($ingresos as $item)
                    <th class="vertical">
                        <div>INGRESOS A {{ $sucursal->nombre }}</div>
                    </th>
                @endforeach
                <th class="vertical bg0">
                    <div>
                        TOTAL INGRESOS {{ $sucursal->nombre }}
                    </div>
                </th>
                @foreach ($salidas as $item)
                    <th class="vertical">
                        <div>SALIDAS DE {{ $sucursal->nombre }}</div>
                    </th>
                @endforeach
                <th class="vertical bg1">
                    <div>
                        TOTAL SALIDAS {{ $sucursal->nombre }}
                    </div>
                </th>
                <th class="vertical bg3">
                    <div>
                        STOCK EN {{ $sucursal->nombre }}
                    </div>
                </th>
                <th class="bg4 vertical">
                    <div>
                        VENTAS
                    </div>
                </th>
                <th class="bgFinal vertical">
                    <div>
                        STOCK {{ $sucursal->nombre }}<br />
                        {{ $horaFinal }}
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->unidad_medida->nombre }}</td>
                    @php
                        $stock_inicial = App\Models\MovimientoHora::where('sucursal_id', $sucursal->id)
                            ->where('producto_id', $producto->id)
                            ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                            ->sum('cantidad_inicial');
                    @endphp
                    <td class="bg2">{{ $stock_inicial }}</td>
                    @php
                        $total_ingresos = 0;
                    @endphp
                    @foreach ($ingresos as $item)
                        @php
                            $cantidad_ingreso = 0;

                            if ($sucursal->almacen == 1) {
                                // central
                                $cantidad_ingreso = App\Models\SolicitudIngresoDetalle::where(
                                    'solicitud_ingreso_id',
                                    $item->id,
                                )
                                    ->where('producto_id', $producto->id)
                                    ->sum('cantidad_fisica');
                            }

                            if ($sucursal->almacen == 2) {
                                // ajuste
                                $cantidad_ingreso = App\Models\Ajuste::where('producto_id', $producto->id)
                                    ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                                    ->sum('cantidad');
                            }

                            if ($sucursal->almacen == 0) {
                                // normal
                                $cantidad_ingreso = App\Models\OrdenSalidaDetalle::where('orden_salida_id', $item->id)
                                    ->where('producto_id', $producto->id)
                                    ->sum('cantidad_fisica');
                            }

                            $total_ingresos += (float) $cantidad_ingreso;
                        @endphp
                        <td>{{ $cantidad_ingreso }}</td>
                    @endforeach
                    <td class="bg0">{{ $total_ingresos }}</td>
                    @php
                        $total_salidas = 0;
                    @endphp
                    @foreach ($salidas as $item)
                        @php
                            $cantidad_salida = 0;

                            if ($sucursal->almacen == 1) {
                                // central
                                $cantidad_salida = App\Models\OrdenSalidaDetalle::where('orden_salida_id', $item->id)
                                    ->where('producto_id', $producto->id)
                                    ->sum('cantidad_fisica');
                            }

                            if ($sucursal->almacen == 2) {
                                // ajuste
                                $cantidad_ingreso = App\Models\AjusteReposicion::where('producto_id', $producto->id)
                                    ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                                    ->sum('cantidad');
                            }

                            if ($sucursal->almacen == 0) {
                                // normal
                                $cantidad_ingreso = App\Models\OrdenVentaDetalle::where('orden_venta_id', $item->id)
                                    ->where('producto_id', $producto->id)
                                    ->sum('cantidad');
                            }
                            $total_salidas += (float) $cantidad_salida;
                        @endphp
                        <td>{{ $cantidad_salida }}</td>
                    @endforeach
                    <td class="bg1">{{ $total_salidas }}</td>
                    @php
                        $ventas = 0;
                        if ($sucursal->almacen == 0) {
                            $ventas = App\Models\OrdenVentaDetalle::whereHas('orden_venta', function ($query) use (
                                $fecha_ini,
                                $fecha_fin,
                            ) {
                                $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                                $query->where('verificado', 2);
                            })
                                ->where('producto_id', $producto->id)
                                ->sum('cantidad');
                        }
                        $stock_sucursal = (float) $stock_inicial + (float) $total_ingresos - (float) $total_salidas;
                    @endphp
                    <td class="bg3">{{ $stock_sucursal }}</td>
                    <td class="bg4">{{ $ventas }}</td>
                    @php
                        $stock_final = App\Models\MovimientoHora::where('sucursal_id', $sucursal->id)
                            ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                            ->sum('cantidad_final');
                    @endphp
                    <td class="bgFinal">{{ $stock_final }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
