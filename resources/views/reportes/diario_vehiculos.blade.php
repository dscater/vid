<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>DiarioVehiculos</title>
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
            height: 160px;
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
    <div class="encabezado">
        <div class="logo">
            <img src="{{ $configuracion->first()->logo_b64 }}">
        </div>
        <h2 class="titulo">
            {{ $configuracion->first()->nombre_sistema }}
        </h2>
        <h4 class="texto">CONTROL DIARIO DE SUCURSALES(VEHÍCULOS)</h4>
        <h4 class="fecha">Fecha: {{ date('d-m-Y', strtotime($fecha)) }}</h4>
    </div>
    @php

    @endphp
    <table border="1">
        <thead>
            <tr>
                <th rowspan="2" width="2.3%">N°</th>
                <th rowspan="2" width="7%">PRODUCTO</th>
                <th rowspan="2">UNIDAD DE MEDIDA</th>
                @foreach ($sucursals as $key => $item)
                    @php
                        $colorIndex = $key % 5;
                        $class = 'bg' . $colorIndex;
                    @endphp
                    <th colspan="5" class={{ $class }}>
                        {{ $item->nombre }}
                    </th>
                @endforeach
                <th colspan="5" class="bgFinal">SALDOS FINALES</th>
            </tr>
            <tr>
                @foreach ($sucursals as $key => $item)
                    @php
                        $colorIndex = $key % 5;
                        $class = 'bg' . $colorIndex;
                    @endphp
                    <th class="vertical {{ $class }}">
                        <div>AÑADIDOS</div>
                    </th>
                    <th class="vertical {{ $class }}">
                        <div>CANTIDAD ENTREGADA</div>
                    </th>
                    <th class="vertical {{ $class }}">
                        <div>DEVOLUCIONES</div>
                    </th>
                    <th class="vertical {{ $class }}">
                        <div>DIFERENCIAS/FALTANTES</div>
                    </th>
                    <th class="vertical {{ $class }}">
                        <div>SALDO FINAL</div>
                    </th>
                @endforeach
                <th class="vertical bgFinal">
                    <div>AÑADIDOS</div>
                </th>
                <th class="vertical bgFinal">
                    <div>CANTIDAD ENTREGADA</div>
                </th>
                <th class="vertical bgFinal">
                    <div>DEVOLUCIONES</div>
                </th>
                <th class="vertical bgFinal">
                    <div>DIFERENCIAS/FALTANTES</div>
                </th>
                <th class="vertical bgFinal">
                    <div>SALDO FINAL</div>
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $cont++ }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td> {{ $producto->unidad_medida->nombre }}</td>

                    @php
                        $total1 = 0;
                        $total2 = 0;
                        $total3 = 0;
                        $total4 = 0;
                        $total5 = 0;
                    @endphp
                    @foreach ($sucursals as $key => $sucursal)
                        @php
                            $colorIndex = $key % 5;
                            $class = 'bg' . $colorIndex;

                            $sucursal_id = $sucursal->id;
                            // INGRESOS ADICIONALES
                            $ingresos_adicionales = App\Models\KardexProducto::where('producto_id', $producto->id)
                                ->where('fecha', $fecha)
                                ->where('tipo_is', 'INGRESO')
                                ->where('sucursal_id', $sucursal_id)
                                ->sum('cantidad_ingreso');

                            // SALDO INICIAL
                            $kardex_inicial = App\Models\KardexProducto::where('producto_id', $producto->id)
                                ->where('fecha', $fecha)
                                ->where('sucursal_id', $sucursal_id)
                                ->get()
                                ->first();
                            if ($kardex_inicial) {
                                if ($kardex_inicial->tipo_is == 'EGRESO') {
                                    $kardex_inicial = App\Models\KardexProducto::where('producto_id', $producto->id)
                                        ->where('id', '<', $kardex_inicial->id)
                                        ->where('tipo_is', 'INGRESO')
                                        ->where('sucursal_id', $sucursal_id)
                                        ->get()
                                        ->last();
                                }
                                $saldo_inicial = $kardex_inicial->cantidad_saldo;
                            } else {
                                $saldo_inicial = 0;
                            }

                            // ENTREGADOS
                            // ventas realizadas
                            $ventas_realizadas = App\Models\OrdenVentaDetalle::where('producto_id', $producto->id);
                            $ventas_realizadas->whereHas('orden_venta', function ($query) use ($fecha, $sucursal_id) {
                                $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                                $query->where('verificado', 2);
                            });
                            $ventas_realizadas = $ventas_realizadas->sum('cantidad');

                            // ventas realizadas
                            $transferencias = App\Models\TransferenciaDetalle::where('producto_id', $producto->id);
                            $transferencias->whereHas('transferencia', function ($query) use ($fecha, $sucursal_id) {
                                $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                            });
                            $transferencias = $transferencias->sum('cantidad_fisica');
                            $total_entregados = (float) $ventas_realizadas + (float) $transferencias;

                            // DEVOLUCIONES
                            $devoluciones = App\Models\DevolucionClienteDetalle::where('producto_id', $producto->id);
                            $devoluciones->whereHas('devolucion_cliente', function ($query) use ($fecha, $sucursal_id) {
                                $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                            });
                            $devoluciones = $devoluciones->sum('cantidad');

                            // FALTANTES
                            $faltantes = App\Models\TransferenciaDetalle::where('producto_id', $producto->id);
                            $faltantes->whereHas('transferencia', function ($query) use ($fecha, $sucursal_id) {
                                $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                            });
                            $faltantes = $faltantes->sum(DB::raw('cantidad - cantidad_fisica'));

                            $faltantes = 0;
                            // SALDO FINAL
                            $kardex_final = App\Models\KardexProducto::where('producto_id', $producto->id)
                                ->where('fecha', $fecha)
                                ->where('sucursal_id', $sucursal_id)
                                ->where('tipo_registro', '!=', 'DEVOLUCIÓN DE STOCK')
                                ->where('id', '>', $kardex_inicial ? $kardex_inicial->id : 0)
                                ->get()
                                ->last();
                            $saldo_final = $kardex_final ? $kardex_final->cantidad_saldo : 0;

                        @endphp
                        <td class="centreado {{ $class }}">{{ $ingresos_adicionales }}</td>
                        <td class="centreado {{ $class }}">{{ $total_entregados }}</td>
                        <td class="centreado {{ $class }}">{{ $devoluciones }}</td>
                        <td class="centreado {{ $class }}">{{ $faltantes }}</td>
                        <td class="centreado {{ $class }}">{{ $saldo_final }}</td>
                        @php
                            $total1 += (float) $ingresos_adicionales;
                            $total2 += (float) $total_entregados;
                            $total3 += (float) $devoluciones;
                            $total4 += (float) $faltantes;
                            $total5 += (float) $saldo_final;
                        @endphp
                    @endforeach
                    <td class="bgFinal">{{ $total1 }}</td>
                    <td class="bgFinal">{{ $total2 }}</td>
                    <td class="bgFinal">{{ $total3 }}</td>
                    <td class="bgFinal">{{ $total4 }}</td>
                    <td class="bgFinal">{{ $total5 }}</td>
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
