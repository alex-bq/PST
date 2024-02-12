<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilla de Proceso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            font-size: 14px;
            overflow-x: hidden;
            /* Oculta la barra de desplazamiento horizontal */
        }

        .container {
            width: 100%;
            position: relative;
            margin-top: 20px;
            padding-left: 20px;
            overflow-x: hidden;
            /* Oculta la barra de desplazamiento horizontal */
        }

        .code-section {
            text-align: left;
            position: absolute;
            top: 0;
            left: 0;
            margin-left: 20px;
            margin-top: 20px;
            font-size: 16px;
            page-break-inside: avoid;
        }

        .section-divider {
            border-top: 1px solid #000;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
            page-break-inside: avoid;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .header-left,
        .header-right {
            width: 30%;
        }

        .header-middle {
            text-align: center;
            width: 40%;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .info-left,
        .info-right {
            width: 49%;
        }

        .info-middle {
            width: 100%;
            text-align: center;
        }

        .data-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .table-container {
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
            width: 100%;
            max-height: 70vh;
            overflow-y: auto;
            page-break-inside: avoid;
        }

        .totals-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .totals-left,
        .totals-right {
            width: 49%;
        }

        @media print {
            body {
                width: 100%;
                height: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container fluid">

        <div class="row">
            <div class="col-sm-3">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" height="50">
            </div>
            <div class="col-sm-6 text-center">
                <h1>Planilla Control Proceso</h1>
            </div>
            <div class="col-sm-3 text-end">
                <h6><strong>N°</strong> {{ $desc_planilla->cod_planilla }}</h6>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-sm-6">
                <p><strong>Lote:</strong> {{ $desc_planilla->lote }}</p>
                <p><strong>Fecha:</strong> {{ $desc_planilla->fec_turno }}</p>
                <p><strong>Turno:</strong> {{ $desc_planilla->turno }}</p>
            </div>

            <div class="col-sm-6 text-end">
                <p><strong>Supervisor:</strong> {{ $desc_planilla->supervisor_nombre }}</p>
                <p><strong>Planillero:</strong> {{ $desc_planilla->planillero_nombre }}</p>
            </div>
        </div>
        <hr class="section-divider">
        <div class="row">
            <div class="col-sm-8">
                <p><strong>Proveedor:</strong> {{ $desc_planilla->proveedor }}</p>
                <p><strong>Empresa:</strong> {{ $desc_planilla->empresa }}</p>
                <p><strong>Especie:</strong> {{ $desc_planilla->especie }}</p>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-6">
                        <p><strong>Entrega Frigorífico</strong></p>
                        <p><strong>Cajas:</strong> {{ $detalle_planilla->cajas_entrega }}</p>
                        <p><strong>Kilos:</strong> {{ round($detalle_planilla->kilos_entrega,2) }}</p>
                        <p><strong>Piezas:</strong> {{ $detalle_planilla->piezas_entrega }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p><strong>Recepción Planta</strong></p>
                        <p><strong>Cajas:</strong> {{ $detalle_planilla->cajas_recepcion }}</p>
                        <p><strong>Kilos:</strong> {{ round($detalle_planilla->kilos_recepcion,2) }}</p>
                        <p><strong>Piezas:</strong> {{ $detalle_planilla->piezas_recepcion }}</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="data-section">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Corte Inicial</th>
                            <th>Corte Final</th>
                            <th>Destino</th>
                            <th>Calibre</th>
                            <th>Calidad</th>
                            <th>Piezas</th>
                            <th>Kilos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($planilla as $registro)
                        <tr>
                            <td>{{ $registro->cInicial }}</td>
                            <td>{{ $registro->cFinal }}</td>
                            <td>{{ $registro->destino }}</td>
                            <td>{{ $registro->calibre }}</td>
                            <td>{{ $registro->calidad }}</td>
                            <td>{{ $registro->piezas }}</td>
                            <td>{{ round($registro->kilos, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr class="section-divider">
        <div class="totals-section">
            <div class="totals-left">
                <p><strong>Dotación:</strong> {{ $detalle_planilla->dotacion }}</p>
            </div>
            <div class="totals-right">
                <p><strong>Observación:</strong> {{ $detalle_planilla->observacion }}</p>
            </div>
        </div>
        <hr class="section-divider">
        <div class="totals-section">
            <div class="totals-left">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Piezas</th>
                            <th>Kilos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($total as $a)
                        <tr>
                            <th>Total</th>
                            <td>{{ $a->totalPiezas }}</td>
                            <td>{{ round($a->totalKilos, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>