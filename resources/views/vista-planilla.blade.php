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
            /* Fondo blanco */
        }

        .container {
            margin-top: 50px;
            width: 100%;
            position: relative;
        }

        .planilla-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .planilla-header-left,
        .planilla-header-right {
            width: 48%;
        }

        .planilla-header-left p,
        .planilla-header-right p {
            margin: 0;
        }

        .planilla-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .planilla-info-left,
        .planilla-info-middle,
        .planilla-info-right {
            width: 30%;
        }

        .planilla-info-middle {
            text-align: center;
        }

        .planilla-registros {
            margin-bottom: 20px;
        }

        .observacion-dotacion {
            margin-top: 20px;
            /* Mover la sección hacia abajo */
        }

        h1,
        h2 {
            color: #000000;
            /* Texto negro */
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #000000;
            /* Borde de tabla negro */
        }

        .table th,
        .table td {
            border: 1px solid #000000;
            /* Borde de celdas negro */
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f2f2f2;
            /* Fondo gris claro para encabezados */
        }

        #totales {
            margin-top: 20px;
            /* Mover la tabla hacia abajo */
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="text-center">Planilla de Proceso</h1>

        <div class="planilla-header">
            <div class="planilla-header-left">
                <p><strong>Fecha de Turno:</strong> {{ $desc_planilla->fec_turno }}</p>
                <p><strong>Turno:</strong> {{ $desc_planilla->turno }}</p>
            </div>

            <div class="planilla-header-right">
                <p><strong>Supervisor:</strong> {{ $desc_planilla->supervisor_nombre }}</p>
                <p><strong>Planillero:</strong> {{ $desc_planilla->planillero_nombre }}</p>
            </div>
        </div>

        <div class="planilla-info">
            <div class="planilla-info-left">
                <p><strong>Código de Planilla:</strong> {{ $desc_planilla->cod_planilla }}</p>
                <p><strong>Proveedor:</strong> {{ $desc_planilla->proveedor }}</p>
                <p><strong>Empresa:</strong> {{ $desc_planilla->empresa }}</p>
                <p><strong>Especie:</strong> {{ $desc_planilla->especie }}</p>
            </div>
            <div class="planilla-info-middle">
                <p><strong>Entrega Frigorífico</strong></p>
                <p><strong>Cajas:</strong> {{ $detalle_planilla->cajas_entrega }}</p>
                <p><strong>Kilos:</strong> {{ $detalle_planilla->kilos_entrega }}</p>
                <p><strong>Piezas:</strong> {{ $detalle_planilla->piezas_entrega }}</p>
            </div>
            <div class="planilla-info-right">
                <p><strong>Recepción Planta</strong></p>
                <p><strong>Cajas:</strong> {{ $detalle_planilla->cajas_recepcion }}</p>
                <p><strong>Kilos:</strong> {{ $detalle_planilla->kilos_recepcion }}</p>
                <p><strong>Piezas:</strong> {{ $detalle_planilla->piezas_recepcion }}</p>
            </div>
        </div>

        <div class="planilla-registros">
            <h2>Registros de la Planilla</h2>
            <div class="table-responsive">
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

        <!-- Tabla de totales -->
        <div class="row">
            <div class="col-md-8">
                <div class="observacion-dotacion">
                    <p><strong>Observación:</strong> {{ $detalle_planilla->observacion }}</p>
                    <p><strong>Dotación:</strong> {{ $detalle_planilla->dotacion }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <table id='totales' class="table table-sm">
                    <thead>
                        <tr>
                            <th class="small"></th>
                            <th class="small">Piezas</th>
                            <th class="small">Kilos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subtotal as $x)
                        <tr>
                            <td>{{ $x->cFinal }}</td>
                            <td>{{ $x->subtotalPiezas }}</td>
                            <td>{{ round($x->subtotalKilos, 2) }}</td>
                        </tr>
                        @endforeach
                        @foreach($total as $a)
                        <tr id="filaTotal">
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