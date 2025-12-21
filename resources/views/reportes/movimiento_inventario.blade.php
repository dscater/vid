<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Kardex</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 1.5cm;
            margin-right: 0.5cm;
            border: 5px solid blue;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 0px;
            page-break-before: avoid;
        }

        table thead {
            page-break-before: always;
        }

        table thead tr th,
        tbody tr td {
            font-size: 0.63em;
            word-break: break-all;
            word-wrap: break-word;
        }

        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            height: 90px;
            top: 0px;
            left: 0px;
        }

        h2.titulo {
            width: 450px;
            margin: auto;
            margin-top: 15px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .fecha {
            width: 250px;
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
            background: #153f59;
            color: white;
        }

        table thead tr th {
            padding: 3px;
            font-size: 0.7em;
        }

        table tbody tr td {
            padding: 3px;
            font-size: 0.7em;
        }

        tr {
            page-break-inside: avoid !important;
        }

        table tbody tr td.franco {
            background: red;
            color: white;
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

        .p_cump {
            color: red;
            font-size: 1.2em;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }

        .producto {
            margin-bottom: -2px;
        }

        .producto tbody tr td {
            font-size: 0.8em;
            font-weight: bold;
            background: rgb(228, 228, 228);
        }

        .nueva_pagina {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @php
        $contador_su = 0;
    @endphp

    @foreach ($sucursals as $sucursal)
        <div class="encabezado">
            <div class="logo">
                <img src="{{ $configuracion->first()->logo_b64 }}">
            </div>
            <h2 class="titulo">
                {{ App\Models\Configuracion::first()->razon_social }}
            </h2>
            <h4 class="texto">KARDEX DE INVENTARIO</h4>
            <h4 class="texto">{{ $sucursal->nombre }}</h4>
            <h4 class="fecha">{{ $array_dias[date('w')] }}, {{ date('d') }} de
                {{ $array_meses[date('m')] }} de {{ date('Y') }}</h4>
            <h4 class="fecha">(Expresado en bolivianos)</h4>
        </div>
        @foreach ($productos as $registro)
            <br><br>
            <table border="1">
                <thead>
                    <tr>
                        <td class="centreado" colspan="10"><strong>{{ $registro->nombre }}
                                {{ $registro->unidad_medida->nombre }}</strong></td>
                    </tr>
                    <tr>
                        <th rowspan="2" width="20px">FECHA</th>
                        <th rowspan="2" width="20px">USUARIO RESPONSABLE</th>
                        <th rowspan="2" width="13%">DETALLE</th>
                        <th colspan="3">CANTIDADES</th>
                        <th rowspan="2">P/U</th>
                        <th colspan="3">BOLIVIANOS</th>
                    </tr>
                    <tr>
                        <th>ENTRADA</th>
                        <th>SALIDA</th>
                        <th>SALDO</th>
                        <th>ENTRADA</th>
                        <th>SALIDA</th>
                        <th>SALDO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $kardex_productos = App\Models\KardexProducto::where('producto_id', $registro->id)->where(
                            'sucursal_id',
                            $sucursal->id,
                        );

                        if ($tipo_movimiento != 'todos') {
                            if ($tipo_movimiento == 'ajuste') {
                                $kardex_productos->where('detalle', 'INGRESO POR AJUSTE');
                            } else {
                                $kardex_productos->where('tipo_is', $tipo_movimiento);
                            }
                        }

                        $kardex_productos->whereIn('detalle', [
                            'ORDEN DE SALIDA',
                            'TRANSFERENCIA',
                            'SOLICITUD INGRESO',
                            'DEVOLUCIÓN DE STOCK',
                        ]);
                        if ($fecha) {
                            $kardex_productos->where('fecha', $fecha);
                        }
                        if ($user_id != 'todos') {
                            $kardex_productos->where('user_id', $user_id);
                        }

                        $kardex_productos = $kardex_productos->get();
                    @endphp
                    @if (count($kardex_productos) > 0)
                        @foreach ($kardex_productos as $key => $value)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($value['fecha'])) }}</td>
                                <td>{{ $value->user->full_name }}</td>
                                <td>{{ $value['detalle'] }}</td>
                                <td class="centreado">{{ $value['cantidad_ingreso'] }}</td>
                                <td class="centreado">{{ $value['cantidad_salida'] }}</td>
                                <td class="centreado">{{ $value['cantidad_saldo'] }}</td>
                                <td class="centreado">{{ number_format($value['cu'], 2, '.', ',') }}</td>
                                <td class="centreado">{{ $value['monto_ingreso'] }}</td>
                                <td class="centreado">{{ $value['monto_salida'] }}</td>
                                <td class="centreado">{{ number_format($value['monto_saldo'], 2, '.', ',') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="centreado">NO SE ENCONTRARON REGISTROS</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach

        @php
            $contador_su++;
        @endphp

        @if ($contador_su < count($sucursals))
            <div class="nueva_pagina"></div>
        @endif
    @endforeach
</body>

</html>
