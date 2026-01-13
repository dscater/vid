<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>DiarioSalidas</title>
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

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }

        .break_page {
            page-break-after: always;
        }

        .obs {
            font-size: 0.9em;
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
    @foreach ($sucursals as $key => $sucursal)
        <div class="encabezado">
            <div class="logo">
                <img src="{{ $configuracion->first()->logo_b64 }}">
            </div>
            <h2 class="titulo">
                {{ $configuracion->first()->nombre_sistema }}
            </h2>
            <h4 class="texto">REPORTE DIARIO DE SALIDAS POR SUCURSAL</h4>
            <h4 class="texto">Sucursal: {{ $sucursal->nombre }}</h4>
            @if ($sucursal->almacen == 0)
                <h4 class="texto">Encargado: {{ $sucursal->user->full_name }}</h4>
            @endif
            <h4 class="fecha">Fecha: {{ date('d-m-Y', strtotime($fecha)) }}</h4>
            {{-- <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4> --}}
        </div>
        @php

        @endphp
        <table border="1">
            <thead>
                <tr>
                    <th width="4%">N°</th>
                    <th>PRODUCTO</th>
                    <th class="bg0">SALDO INICIAL</th>
                    <th class="bg1">VENTAS REALIZADAS</th>
                    <th class="bg2">DEVOLUCIONES</th>
                    <th class="bg3">PRODUCTOS AÑADIDOS</th>
                    <th class="bg4">SALDO FINAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                    $sucursal_id = $sucursal->id;
                @endphp
                @foreach ($productos as $producto)
                    @php
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

                        // ventas realizadas
                        $ventas_realizadas = App\Models\OrdenVentaDetalle::where('producto_id', $producto->id);
                        $ventas_realizadas->whereHas('orden_venta', function ($query) use ($fecha, $sucursal_id) {
                            $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                            $query->where('verificado', 2);
                        });
                        $ventas_realizadas = $ventas_realizadas->sum('cantidad');

                        // DEVOLUCIONES
                        $devoluciones = App\Models\DevolucionClienteDetalle::where('producto_id', $producto->id);
                        $devoluciones->whereHas('devolucion_cliente', function ($query) use ($fecha, $sucursal_id) {
                            $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                        });
                        $devoluciones = $devoluciones->sum('cantidad');

                        // INGRESOS ADICIONALES
                        $ingresos_adicionales = App\Models\KardexProducto::where('producto_id', $producto->id)
                            ->where('fecha', $fecha)
                            ->where('tipo_is', 'INGRESO')
                            ->where('sucursal_id', $sucursal_id)
                            ->sum('cantidad_ingreso');
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
                    <tr>
                        <td>{{ $cont++ }}</td>
                        <td>{{ $producto->nombre }} {{ $producto->unidad_medida->nombre }}</td>
                        <td class="centreado">{{ $saldo_inicial }}</td>
                        <td class="centreado">{{ $ventas_realizadas }}</td>
                        <td class="centreado">{{ $devoluciones }}</td>
                        <td class="centreado">{{ $ingresos_adicionales }}</td>
                        <td class="centreado">{{ $saldo_final }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class='obs'>Observaciones:</p>
        <p class='punteado'></p>
        <p class='punteado'></p>
        <p class='punteado'></p>

        @if ($key < count($sucursals) - 1)
            <div class="break_page"></div>
        @endif
    @endforeach
</body>

</html>
