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
        }

        p {
            margin-bottom: 8px;

        }

        .section-divider {
            border-top: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .data-section {
            page-break-inside: avoid;
        }

        .table-container {
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            max-height: 70vh;
            overflow-y: auto;
            page-break-inside: avoid;

        }

        @media print {
            #esconder {
                display: none;
            }
        }

        @media (max-width: 576px) {

            .text-end,
            .text-center {
                text-align: left !important;
            }

        }
    </style>



</head>

<body>
    <div id="esconder" class="container">
        <div class="row">
            <div class="col-sm-6">
                <a title="Editar" class="btn" href="{{ url('/planilla/' . $desc_planilla->cod_planilla) }}"
                    target="iframeContent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"
                        style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                        <path d="m16 2.012 3 3L16.713 7.3l-3-3zM4 14v3h3l8.299-8.287-3-3zm0 6h16v2H4z"></path>
                    </svg> </a>

            </div>
            <div class="col-sm-6 text-end">
                <button title="Descargar PDF" class="btn" onclick="descargarPDF()"><svg
                        xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"
                        style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                        <path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path>
                    </svg></button>
            </div>
        </div>

    </div>

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-3">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" height="50">
            </div>
            <div class="col-sm-6 text-center">
                <h2>Planilla Control Proceso</h2>
            </div>
            <div class="col-sm-3 text-end">
                <h6><strong>N°</strong> {{ $desc_planilla->cod_planilla }}</h6>
                <p>{{ $desc_planilla->sala }}</p>
            </div>
        </div>
        <hr class="section-divider">

        <div class="row">
            <div class="col-sm-4">
                <p><strong>Lote:</strong> {{ $desc_planilla->lote }}</p>
                <p><strong>Fecha:</strong> {{ $desc_planilla->fec_turno }}</p>
                <p><strong>Turno:</strong> {{ $desc_planilla->turno }}</p>
            </div>

            <div class="col-sm-4 text-center">
                <p><strong>Proveedor:</strong> {{ $desc_planilla->proveedor }}</p>
                <p><strong>Empresa:</strong> {{ $desc_planilla->empresa }}</p>
                <p><strong>Especie:</strong> {{ $desc_planilla->especie }}</p>
            </div>

            <div class="col-sm-4 text-end">
                <p><strong>Supervisor:</strong> {{ $desc_planilla->supervisor_nombre }}</p>
                <p><strong>Planillero:</strong> {{ $desc_planilla->planillero_nombre }}</p>
                <p><strong>Dotación:</strong> {{ $detalle_planilla->dotacion }}</p>
            </div>
        </div>
        <hr class="section-divider">
        <div class="row">
            <div class="col-sm-8">

            </div>
            <div class="col-sm-4">

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
        <div class="row">
            <div class="col-sm-6">
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
            <div class="col-sm-6">
                <table style="font-size: 11px; line-height: 1;" class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Piezas</th>
                            <th>Kilos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subtotal as $x)
                        <tr>
                            <td>{{$x->cFinal}}</td>
                            <td>{{$x->subtotalPiezas}}</td>
                            <td>{{round($x->subtotalKilos,2)}}</td>
                        </tr>


                        @endforeach
                        @foreach($total as $a)
                        <tr>
                            <th>Total</th>
                            <td>{{$a->totalPiezas}}</td>
                            <td>{{round($a->totalKilos,2)}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
        <hr class="section-divider">
        <div class="row">
            <p><strong>Observación:</strong> {{ $detalle_planilla->observacion }}</p>

        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function descargarPDF() {
            // Invocar la impresión
            window.print();
        }
    </script>


</body>

</html>