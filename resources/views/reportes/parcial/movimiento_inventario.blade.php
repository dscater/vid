@inject('configuracion', 'App\Models\Configuracion')
@inject('parametroSucursal', 'App\Models\ParametroSucursal')
@php
    $horaInicial = date('H:i', strtotime($parametroSucursal->first()->valor1)) ?? '08:00';
    $horaFinal = date('H:i', strtotime($parametroSucursal->first()->valor2)) ?? '20:00';
@endphp
<div class="encabezado">
    <h2 class="titulo">
        {{ $configuracion->first()->nombre_sistema }}
    </h2>
    <h4 class="texto">MOVIMIENTOS DE INVENTARIO</h4>
    <h4 class="fecha">Del {{ date('d/m/Y', strtotime($fecha_ini)) }} al {{ date('d/m/Y', strtotime($fecha_fin)) }}
    </h4>
</div>
@php

@endphp
<table border="1">
    <thead>
        <tr>
            <th width="7%">PRODUCTO</th>
            <th>UNIDAD DE MEDIDA</th>
            @foreach ($sucursals as $key => $item)
                <th class="vertical">
                    <div>
                        STOCK INICIAL {{ $item->nombre }}<br />
                        {{ $horaInicial }}
                    </div>
                </th>
            @endforeach
            <th class="bgFinal vertical">
                <div>
                    TOTAL STOCK INICIAL<br />
                    {{ $horaInicial }}
                </div>
            </th>
            @foreach ($sucursals as $key => $item)
                <th class="vertical">
                    <div>
                        VENTAS {{ $item->nombre }}
                    </div>
                </th>
            @endforeach
            <th class="bgFinal vertical">
                <div>
                    TOTAL VENTAS
                </div>
            </th>
            @foreach ($sucursals as $key => $item)
                <th class="vertical">
                    <div>
                        STOCK FINAL {{ $item->nombre }}<br />
                        {{ $horaFinal }}
                    </div>
                </th>
            @endforeach
            <th class="bgFinal vertical">
                <div>
                    TOTAL STOCK FINAL<br />
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
                    $total_inicial = 0;
                @endphp
                @foreach ($sucursals as $key => $item)
                    @php
                        $stock_inicial = App\Models\MovimientoHora::where('sucursal_id', $item->id)
                            ->where('producto_id', $producto->id)
                            ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                            ->sum('cantidad_inicial');
                        $total_inicial += (float) $stock_inicial;
                    @endphp
                    <td>
                        {{ $stock_inicial }}
                    </td>
                @endforeach
                <td>{{ $total_inicial }}</td>
                @php
                    $total_ventas = 0;
                @endphp
                @foreach ($sucursals as $key => $item)
                    @php
                        $cantidad_vendida = App\Models\OrdenVentaDetalle::whereHas('orden_venta', function (
                            $query,
                        ) use ($fecha_ini, $fecha_fin, $item) {
                            $query->where('sucursal_id', $item->id);
                            $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                            $query->where('verificado', 2);
                        })
                            ->where('producto_id', $producto->id)
                            ->sum('cantidad');
                        $total_ventas += (float) $cantidad_vendida;
                    @endphp
                    <td>
                        {{ $cantidad_vendida }}
                    </td>
                @endforeach
                <td>{{ $total_ventas }}</td>
                @php
                    $total_final = 0;
                @endphp
                @foreach ($sucursals as $key => $item)
                    @php
                        $stock_final = App\Models\MovimientoHora::where('sucursal_id', $item->id)
                            ->where('producto_id', $producto->id)
                            ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                            ->sum('cantidad_final');
                        $total_final += (float) $stock_final;
                    @endphp
                    <td>
                        {{ $stock_final }}
                    </td>
                @endforeach
                <td>{{ $total_final }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
