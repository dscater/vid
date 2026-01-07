<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>MovimientoInventario</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1.5cm;
            margin-bottom: 0.3cm;
            margin-left: 0.3cm;
            margin-right: 0.3cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
            page-break-before: avoid;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
        }

        table thead tr th {
            font-size: 7pt;
        }

        table tbody tr td {
            font-size: 6pt;
        }


        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            height: 70px;
            top: -20px;
            left: 0px;
        }

        h2.titulo {
            width: 450px;
            margin: auto;
            margin-top: 0PX;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 350px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1em;
        }

        .fecha {
            width: 350px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: normal;
            font-size: 0.85em;
        }

        .total {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        tr {
            page-break-inside: avoid !important;
        }

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .datos {
            margin-left: 15px;
            border-top: solid 1px;
            border-collapse: collapse;
            width: 250px;
        }

        .txt {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
        }

        .txt_center {
            font-weight: bold;
            text-align: center;
        }

        .cumplimiento {
            position: absolute;
            width: 150px;
            right: 0px;
            top: 86px;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .bg-principal {
            background: #153f59;
            color: white;
        }

        th.vertical {
            vertical-align: middle;
            text-align: center;
            height: 220px;
            /* controla la altura de la fila */
            padding: 0;
        }

        th.vertical div {
            transform: rotate(-90deg);
            white-space: nowrap;
            font-size: 7pt;
        }

        .img_celda img {
            width: 45px;
        }

        .break_page {
            page-break-after: always;
        }

        .obs {
            font-size: 0.9em;
        }

        .bgFinal {
            background-color: rgb(255, 255, 232);
            font-weight: bold;
            font-size: 7.6pt;
        }

        .punteado {
            display: block;
            width: 100%;
            border-bottom: dotted 1px black;
            margin-top: 25px;
        }

        .bg0 {
            background: #cff3f3;
        }

        .bg1 {
            background: #ffe9ff;
        }

        .bg2 {
            background: #f7ffe0;
        }

        .bg3 {
            background: #ecfcdd;
        }

        .bg4 {
            background: #faeee4;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @inject('parametroSucursal', 'App\Models\ParametroSucursal')
    @php
        $horaInicial = date('H:i', strtotime($parametroSucursal->first()->valor1)) ?? '08:00';
        $horaFinal = date('H:i', strtotime($parametroSucursal->first()->valor2)) ?? '20:00';
        $sucursal = $sucursals[0];
    @endphp
    <div class="encabezado">
        <div class="logo">
            <img src="{{ $configuracion->first()->logo_b64 }}">
        </div>
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

    <p class='obs'>Observaciones:</p>
    <p class='punteado'></p>
    <p class='punteado'></p>
    <p class='punteado'></p>

</body>

</html>
