<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>OrdenVentas</title>
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
            background: rgb(241, 241, 241);
        }

        .bg-principal {
            background: #153f59;
            color: white;
        }

        .bold {
            font-weight: bold;
        }

        .img_celda img {
            width: 45px;
        }

        .derecha {
            text-align: right;
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
        <h4 class="texto">ORDENES DE VENTAS</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
    </div>
    @foreach ($orden_ventas as $item)
        <table border="1">
            <tbody>
                <tr>
                    <td class="bold" width="6%">Nro.: </td>
                    <td>{{ $item->codigo }}</td>
                    <td class="bold">Cliente: </td>
                    <td>{{ $item->cliente->razon_social }}</td>
                    <td class="bold">Empleado: </td>
                    <td colspan="2">{{ $item->user->full_name }}</td>
                </tr>
                <tr>
                    <td class="bold">Fecha: </td>
                    <td>{{ $item->fecha_c }}</td>
                    <td class="bold">Forma de pago: </td>
                    <td>{{ $item->forma_pago }}</td=>
                    <td class="bold">Sucursal: </td>
                    <td colspan="2">{{ $item->sucursal->nombre }}</td>
                </tr>
                <tr>
                    <td class="bold">Estado: </td>
                    <td colspan="6">{{ $item->estado }}</td>
                </tr>
                <tr class="gray">
                    <th>N°</th>
                    <th>CANTIDAD</th>
                    <th>DESCRIPCIÓN</th>
                    <th>P/U</th>
                    <th>SUBTOTAL</th>
                    <th>DESCUENTO</th>
                    <th>TOTAL</th>
                </tr>
                @foreach ($item->orden_venta_detalles as $key => $si)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $si->cantidad }}</td>
                        <td>{{ $si->producto->nombre }} {{ $si->producto->unidad_medida->nombre }}</td>
                        <td class="derecha">{{ $si->precio }}</td>
                        <td class="derecha">{{ $si->subtotal }}</td>
                        <td class="derecha">{{ $si->descuento }}</td>
                        <td class="derecha">{{ $si->subtotal_f }}</td>
                    </tr>
                @endforeach
                <tr class="gray">
                    <th colspan="6">TOTAL</th>
                    <th>{{ $item->total_st }}</th>
                </tr>
                <tr class="gray">
                    <th colspan="6">DESCUENTO</th>
                    <th>{{ $item->descuento ?? 0 }}</th>
                </tr>
                <tr class="gray">
                    <th colspan="6">TOTAL FINAL</th>
                    <th>{{ $item->total_f ?? 0 }}</th>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>

</html>
