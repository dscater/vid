<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CuentaCobrar</title>
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

        table th {
            font-size: 8pt;
        }

        table tbody tr td {
            font-size: 7pt;
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
        <h4 class="texto">CUENTAS POR COBRAR</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
    </div>
    <table border="1">
        <thead class="bg-principal">
            <tr>
                <th width="3%">N°</th>
                <th>FECHA</th>
                <th>CLIENTE</th>
                <th>CÓD. ORDEN VENTA</th>
                <th>TOTAL</th>
                <th>CANCELADO</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @foreach ($cuenta_cobrars as $item)
                <tr>
                    <td class="centreado">{{ $cont++ }}</td>
                    <td class="">{{ $item->fecha_c }}</td>
                    <td class="">{{ $item->cliente->razon_social }}</td>
                    <td class="">{{ $item->orden_venta->codigo }}</td>
                    <td class="centreado">Bs. {{ $item->total }}</td>
                    <td class="centreado">Bs. {{ $item->cancelado }}</td>
                    <td class="centreado">Bs. {{ $item->saldo }}</td>
                </tr>
            @endforeach
            @php
                $total = $cuenta_cobrars->sum('total');
                $cancelado = $cuenta_cobrars->sum('cancelado');
                $saldo = $cuenta_cobrars->sum('saldo');
            @endphp
            <tr>
                <th colspan="4">TOTAL</th>
                <th>Bs.{{ $total }}</th>
                <th>Bs.{{ $cancelado }}</th>
                <th>Bs.{{ $saldo }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
