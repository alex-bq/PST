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
                <p style="margin-bottom: 0px;"><strong>{{ $desc_planilla->tipo_planilla_nombre ?? 'N/A' }}</strong></p>
            </div>
            <div class="col-sm-3 text-end">
                <h6><strong>N°</strong> {{ $desc_planilla->cod_planilla }}</h6>
                <p>{{ $desc_planilla->sala }}</p>
            </div>
        </div>
        <hr class="section-divider">

        <div class="row">
            <div class="col-sm-3">
                <p><strong>Lote:</strong> {{ $desc_planilla->lote }}</p>
                <p><strong>Fecha:</strong> {{ $desc_planilla->fec_turno }}</p>
                <p><strong>Turno:</strong> {{ $desc_planilla->turno }}</p>
            </div>

            <div class="col-sm-3 text-center">
                <p><strong>Proveedor:</strong> {{ $desc_planilla->proveedor }}</p>
                <p><strong>Empresa:</strong> {{ $desc_planilla->empresa }}</p>
                <p><strong>Especie:</strong> {{ $desc_planilla->especie }}</p>
            </div>

            <div class="col-sm-3">
                <p><strong>Supervisor:</strong> {{ $desc_planilla->supervisor_nombre }}</p>
                <p><strong>Planillero:</strong> {{ $desc_planilla->planillero_nombre }}</p>
                <p><strong>Jefe Turno:</strong> {{ $desc_planilla->jefe_turno_nombre ?? 'N/A' }}</p>
            </div>

            <div class="col-sm-3 text-end">
                <p><strong>Dotación:</strong> {{ $detalle_planilla->dotacion }}</p>
                <p><strong>Inicio:</strong>
                    {{ $desc_planilla->hora_inicio ? \Carbon\Carbon::parse($desc_planilla->hora_inicio)->format('H:i') : 'N/A' }}
                </p>
                <p><strong>Término:</strong>
                    {{ $desc_planilla->hora_termino ? \Carbon\Carbon::parse($desc_planilla->hora_termino)->format('H:i') : 'N/A' }}
                </p>
                @if($desc_planilla->hora_inicio && $desc_planilla->hora_termino)
                    @php
                        $inicio = \Carbon\Carbon::parse($desc_planilla->hora_inicio);
                        $termino = \Carbon\Carbon::parse($desc_planilla->hora_termino);
                        if ($termino < $inicio) {
                            $termino->addDay();
                        }
                        $tiempoTrabajado = $inicio->diffInMinutes($termino) / 60;
                    @endphp
                    <p><strong>Trabajado:</strong> {{ number_format($tiempoTrabajado, 1) }}h</p>
                @else
                    <p><strong>Trabajado:</strong> N/A</p>
                @endif
            </div>
        </div>
        <hr class="section-divider">

        <!-- Sección de KPIs -->
        <div class="row">
            <div class="col-12">
                <div class="row text-center">
                    <div class="col-sm-4">
                        <p><strong>Productividad</strong></p>
                        <p style="font-size: 1.1em; color: #2c3e50;">
                            {{ $detalle_planilla->productividad ? number_format($detalle_planilla->productividad, 2) : '0.00' }}
                            kg/pers/hr
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <p><strong>Rendimiento General</strong></p>
                        <p style="font-size: 1.1em; color: #2c3e50;">
                            @php
                                $rendimiento_general = ($detalle_planilla->kilos_entrega > 0)
                                    ? ($detalle_planilla->kilos_recepcion / $detalle_planilla->kilos_entrega) * 100
                                    : 0;
                            @endphp
                            {{ number_format($rendimiento_general, 2) }}%
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <p><strong>Rendimiento Objetivo</strong></p>
                        <p style="font-size: 1.1em; color: #2c3e50;">
                            @php
                                $rendimiento_objetivo = ($detalle_planilla->kilos_entrega > 0)
                                    ? ($kilos_objetivo / $detalle_planilla->kilos_entrega) * 100
                                    : 0;
                            @endphp
                            {{ number_format($rendimiento_objetivo, 2) }}%
                        </p>
                    </div>
                </div>
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
                            <th class="text-center">Objetivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($planilla as $registro)
                            <tr
                                style="{{ isset($registro->es_producto_objetivo) && $registro->es_producto_objetivo == 1 ? 'background-color: #fff9c4;' : '' }}">
                                <td>{{ $registro->cInicial }}</td>
                                <td>{{ $registro->cFinal }}</td>
                                <td>{{ $registro->destino }}</td>
                                <td>{{ $registro->calibre }}</td>
                                <td>{{ $registro->calidad }}</td>
                                <td>{{ $registro->piezas }}</td>
                                <td>{{ round($registro->kilos, 2) }}</td>
                                <td class="text-center">
                                    @if(isset($registro->es_producto_objetivo) && $registro->es_producto_objetivo == 1)
                                        <span style="color: #2c5530; font-weight: bold;">SÍ</span>
                                    @else
                                        <span style="color: #666;">NO</span>
                                    @endif
                                </td>
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
                    <div class="col-sm-4">
                        <p><strong>Entrega Frigorífico</strong></p>
                        <p><strong>Kilos:</strong> {{ round($detalle_planilla->kilos_entrega, 2) }}</p>
                        <p><strong>Piezas:</strong> {{ $detalle_planilla->piezas_entrega }}</p>
                    </div>
                    <div class="col-sm-4">
                        <p><strong>Recepción Planta</strong></p>
                        <p><strong>Kilos:</strong> {{ round($detalle_planilla->kilos_recepcion, 2) }}</p>
                        <p><strong>Piezas:</strong> {{ $detalle_planilla->piezas_recepcion }}</p>
                    </div>
                    <div class="col-sm-4">
                        <p><strong>Objetivo</strong></p>
                        <p><strong>Kilos:</strong> {{ number_format($kilos_objetivo, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <table id='totales' class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small text-nowrap px-3">Corte Final</th>
                            <th class="small px-3">Calidad</th>
                            <th class="small text-end px-3">Piezas</th>
                            <th class="small text-end px-3">Kilos</th>
                            <th class="small text-end px-3">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subtotal as $x)
                            <tr>
                                <td class="px-3">{{$x->corte_final}}</td>
                                <td class="px-3">{{$x->calidad}}</td>
                                <td class="text-end px-3">{{number_format($x->total_piezas, 0, '.', ',')}}</td>
                                <td class="text-end px-3">{{number_format($x->total_kilos, 2, '.', ',')}}</td>
                                <td class="text-end px-3">
                                    {{number_format($x->porcentaje_del_total, 2, '.', ',')}}%
                                </td>
                            </tr>
                        @endforeach
                        @foreach($total as $a)
                            <tr id="filaTotal" class="table-secondary fw-bold">
                                <th class="px-3">{{$a->corte_final}}</th>
                                <th class="px-3">{{$a->calidad}}</th>
                                <td class="text-end px-3">{{number_format($a->total_piezas, 0, '.', ',')}}</td>
                                <td class="text-end px-3">{{number_format($a->total_kilos, 2, '.', ',')}}</td>
                                <td class="text-end px-3">
                                    {{number_format($a->porcentaje_del_total, 2, '.', ',')}}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <hr class="section-divider">

        <!-- Sección de Tiempos Muertos -->
        @if($tiempos_muertos && count($tiempos_muertos) > 0)
            <div class="row">
                <div class="col-12">
                    <h6><strong>Tiempos Muertos</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Departamento</th>
                                    <th>Causa</th>
                                    <th class="text-center">Hora Inicio</th>
                                    <th class="text-center">Hora Término</th>
                                    <th class="text-center">Duración (min)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tiempos_muertos as $tiempo)
                                    <tr>
                                        <td>{{ $tiempo->departamento_nombre }}</td>
                                        <td>{{ $tiempo->causa }}</td>
                                        <td class="text-center">
                                            {{ $tiempo->hora_inicio ? \Carbon\Carbon::parse($tiempo->hora_inicio)->format('H:i') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $tiempo->hora_termino ? \Carbon\Carbon::parse($tiempo->hora_termino)->format('H:i') : '-' }}
                                        </td>
                                        <td class="text-center">{{ $tiempo->duracion_minutos ?? '-' }}</td>
                                    </tr>
                                @endforeach
                                @if(count($tiempos_muertos) > 1)
                                    <tr class="table-secondary">
                                        <td colspan="4" class="text-end"><strong>Total Tiempo Muerto:</strong></td>
                                        <td class="text-center"><strong>{{ $tiempos_muertos->sum('duracion_minutos') }}
                                                min</strong></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr class="section-divider">
        @endif

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