@extends('layouts.main-iframe')

@section('title', 'Detalle del Turno')

@section('styles')
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #000120;
            background-color: #f8f9fa;
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .back-button {
            background-color: #1a237e;
            color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .back-button:hover {
            background-color: #000120;
            color: #f8f9fa;
        }

        .page-title {
            color: #14142a;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 2rem;
        }

        .page-subtitle {
            color: #000120;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .turno-info {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .turno-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .turno-info-item {
            text-align: center;
        }

        .turno-info-item strong {
            display: block;
            color: #14142a;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .turno-info-item span {
            color: #000120;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .tipo-planilla-title {
            color: #14142a;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .salas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;

        }

        .sala-card {}

        .sala-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #14142a;
            margin-bottom: 1rem;
        }

        .sala-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));

        }

        .sala-info-item {
            margin-bottom: 0.5rem;
        }

        .sala-info-item strong {
            display: block;
            color: #14142a;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .sala-info-item span,
        .sala-info-item input {
            color: #000120;
            font-size: 1rem;
        }

        .dotacion-input {
            width: 60%;
            border: 1px solid #14142a38;
            border-radius: 4px;
            text-align: center;
        }

        .dotacion-esperada-input {
            width: 60%;
            border: 1px solid #14142a38;
            border-radius: 4px;
            text-align: center;
        }

        .btn-detail {
            background-color: #1a237e;
            color: #f8f9fa;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-detail:hover {
            background-color: #000120;
        }

        .modal-header {
            background-color: #14142a;
            color: #f8f9fa;
        }

        .modal-title {
            color: #f8f9fa;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table th,
        .detail-table td {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
        }

        .detail-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #14142a;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <a href="javascript:history.back()" class="back-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
            </svg>
            Volver
        </a>

        <h2 class="page-title">Detalle del Turno</h2>
        <h3 class="page-subtitle">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} - {{ $informe->turno }} -
            {{ $informe->jefe_turno_nom }}
        </h3>

        <div class="turno-info">
            <div class="turno-info-grid">
                <div class="turno-info-item">
                    <strong>Dotación Esperada Total</strong>
                    <span id="dotacion-esperada-total">-</span>
                </div>
                <div class="turno-info-item">
                    <strong>Dotación Total</strong>
                    <span id="dotacion-total">-</span>
                </div>
                <div class="turno-info-item">
                    <strong>Ausentismo</strong>
                    <span id="porcentaje-ausentismo">-</span>
                </div>

            </div>
        </div>

        @php
            $tipos_planilla = collect($informacion_sala)->groupBy('tipo_planilla');
        @endphp

        @foreach($tipos_planilla as $tipo_planilla => $salas)
            <div class="tipo-planilla-section">
                <h3 class="tipo-planilla-title">{{ $tipo_planilla }}</h3>
                <div class="salas-grid">
                    @foreach($salas as $sala)
                            <div class="card w-full max-w-2xl mb-[10px] sala-card" data-sala-id="{{ $sala->cod_sala }}"
                                data-sala-nombre="{{ $sala->nombre_sala }}" data-tipo-planilla="{{ $sala->tipo_planilla }}"
                                data-piezas-recepcion="{{ $sala->piezas_recepcion_total }}">
                                <div class="card-header bg-primary/5 pb-2">
                                    <div class="text-xl flex items-center justify-between">
                                        <span class="flex items-center gap-2">

                                            {{ $sala->nombre_sala }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body pt-4">
                                    <div class="row">
                                        <!-- Dotación and Indicadores in first row -->
                                        <!-- Dotación Section -->
                                        <div class="mb-4">
                                            <h5 class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="text-primary me-2">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                                Dotación
                                            </h5>
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="form-label text-muted small mb-1">Real</label>
                                                    <input type="number" class="form-control form-control-sm dotacion-input" min="0"
                                                        value="0" data-sala-id="{{ $sala->cod_sala }}" onclick="this.select()"
                                                        onchange="actualizarDotacionTotal()">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted small mb-1">Esperada</label>
                                                    <input type="number" class="form-control form-control-sm dotacion-esperada-input"
                                                        min="0" value="0" data-sala-id="{{ $sala->cod_sala }}" onclick="this.select()"
                                                        onchange="actualizarDotacionTotal()">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Indicadores Section -->
                                        <div>
                                            <h5 class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="text-primary me-2">
                                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                                </svg>
                                                Indicadores
                                            </h5>
                                            <div class="d-flex flex-row flex-wrap gap-3">
                                                @php
                                                    $procesamientoSala = collect($detalle_procesamiento)
                                                        ->where('cod_sala', $sala->cod_sala)
                                                        ->where('cod_tipo_planilla', $sala->cod_tipo_planilla);

                                                    $rendimientoPremium = 0;
                                                    if ($sala->kilos_recepcion_total > 0) {
                                                        $rendimientoPremium = ($procesamientoSala->where('calidad', 'PREMIUM')->sum('kilos') / $sala->kilos_recepcion_total) * 100;
                                                    }

                                                    $rendimientoGeneral = 0;
                                                    if ($sala->kilos_entrega_total > 0) {
                                                        $rendimientoGeneral = ($procesamientoSala->sum('kilos') / $sala->kilos_entrega_total) * 100;
                                                    }
                                                @endphp

                                                <!-- Indicador Premium/Rendimiento General -->
                                                <div class="flex-grow-1">
                                                    <p class="text-muted small mb-1">
                                                        {{ $sala->tipo_planilla == 'Porciones' ? 'Rendimiento General' : 'Premium' }}
                                                    </p>
                                                    <p class="fw-medium premium-valor">
                                                        {{ number_format($rendimientoPremium, 1) }}%
                                                    </p>
                                                </div>

                                                <!-- Rendimiento General (solo para no-Porciones) -->
                                                @if($sala->tipo_planilla != 'Porciones')
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted small mb-1">Rendimiento</p>
                                                        <p class="fw-medium rendimiento-valor">
                                                            {{ number_format($rendimientoGeneral, 1) }}%
                                                        </p>
                                                    </div>
                                                @endif

                                                <!-- Productividad -->
                                                <div class="flex-grow-1">
                                                    <p class="text-muted small mb-1">Productividad</p>
                                                    <p class="fw-medium productividad-valor">-</p>
                                                </div>

                                                <!-- Horas Trabajadas -->
                                                <div class="flex-grow-1">
                                                    <p class="text-muted small mb-1">Horas Trabajadas</p>
                                                    @php
                                                        $horasEnteras = floor($sala->horas_trabajadas);
                                                        $minutos = round(($sala->horas_trabajadas - $horasEnteras) * 60);
                                                    @endphp
                                                    <p class="fw-medium" data-horas-trabajadas="{{ $sala->horas_trabajadas }}">
                                                        {{ $horasEnteras }}h {{ $minutos }}m
                                                    </p>
                                                </div>

                                                <!-- Tiempo Muerto -->
                                                <div class="flex-grow-1">
                                                    <p class="text-muted small mb-1">Tiempo Muerto</p>
                                                    @php
                                                        $minutosTiempoMuerto = collect($tiempos_muertos)
                                                            ->where('cod_sala', $sala->cod_sala)
                                                            ->sum('duracion_minutos');
                                                        $horasTM = floor($minutosTiempoMuerto / 60);
                                                        $minutosTM = $minutosTiempoMuerto % 60;
                                                    @endphp
                                                    <p class="fw-medium" data-tiempo-muerto="{{ $minutosTiempoMuerto }}">
                                                        {{ $horasTM }}h {{ $minutosTM }}m
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-2">

                                    <!-- Material Processing Section -->
                                    <div class="row">
                                        <!-- Entrega MP -->
                                        <div class="col-md-6 mb-4 mb-md-0">
                                            <div>
                                                <h5 class="d-flex align-items-center">
                                                    Entrega MP
                                                </h5>
                                                @php
                                                    $procesamientoSala = collect($detalle_procesamiento)
                                                        ->where('cod_sala', $sala->cod_sala)
                                                        ->where('cod_tipo_planilla', $sala->cod_tipo_planilla);
                                                @endphp
                                                <div class="d-flex flex-row flex-wrap gap-3">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted small mb-1">Kilos</p>
                                                        <p class="fw-medium" data-kilos-entrega="{{ $sala->kilos_entrega_total }}">
                                                            {{ number_format($sala->kilos_entrega_total, 1) }} kg
                                                        </p>
                                                    </div>
                                                    @if($sala->tipo_planilla !== 'Porciones')
                                                        <div class="flex-grow-1">
                                                            <p class="text-muted small mb-1">Piezas</p>
                                                            <p class="fw-medium" data-piezas-entrega="{{ $sala->piezas_entrega_total }}">
                                                                {{ number_format($sala->piezas_entrega_total, 0) }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Recepción PST -->
                                        <div class="col-md-6">
                                            <div>
                                                <h5 class="d-flex align-items-center">
                                                    Recepción PST
                                                </h5>
                                                <div class="d-flex flex-row flex-wrap gap-3">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted small mb-1">Kilos</p>
                                                        <p class="font-medium"
                                                            data-kilos-recepcion="{{ number_format($procesamientoSala->sum('kilos'), 1) }}">
                                                            {{ number_format($procesamientoSala->sum('kilos'), 1) }} kg
                                                        </p>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted small mb-1">Kilos Premium</p>
                                                        @php
                                                            $kilosPremium = $procesamientoSala->where('calidad', 'PREMIUM')->sum('kilos');
                                                        @endphp
                                                        <p class="font-medium"
                                                            data-kilos-premium="{{ number_format($kilosPremium, 1) }}">
                                                            {{ number_format($kilosPremium, 1) }} kg
                                                        </p>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted small mb-1">Piezas</p>
                                                        <p class="fw-medium"
                                                            data-piezas-recepcion="{{ number_format($procesamientoSala->sum('piezas'), decimals: 0) }}">
                                                            {{ number_format($procesamientoSala->sum('piezas'), decimals: 0) }}
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex mt-4">
                                        <button class="btn btn-primary btn-detail" data-bs-toggle="modal"
                                            data-bs-target="#procesamiento{{ $sala->cod_tipo_planilla }}_{{ $sala->cod_sala }}"
                                            data-bs-tooltip="tooltip" title="Ver Detalle Procesamiento">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path
                                                    d="M8 6.00067L21 6.00139M8 12.0007L21 12.0015M8 18.0007L21 18.0015M3.5 6H3.51M3.5 12H3.51M3.5 18H3.51M4 6C4 6.27614 3.77614 6.5 3.5 6.5C3.22386 6.5 3 6.27614 3 6C3 5.72386 3.22386 5.5 3.5 5.5C3.77614 5.5 4 5.72386 4 6ZM4 12C4 12.2761 3.77614 12.5 3.5 12.5C3.22386 12.5 3 12.2761 3 12C3 11.7239 3.22386 11.5 3.5 11.5C3.77614 11.5 4 11.7239 4 12ZM4 18C4 18.2761 3.77614 18.5 3.5 18.5C3.22386 18.5 3 18.2761 3 18C3 17.7239 3.22386 17.5 3.5 17.5C3.77614 17.5 4 17.7239 4 18Z" />
                                            </svg>
                                        </button>
                                        <button class="btn btn-primary btn-detail ms-2" data-bs-toggle="modal"
                                            data-bs-target="#tiempos{{ $sala->cod_tipo_planilla }}_{{ $sala->cod_sala }}"
                                            data-bs-tooltip="tooltip" title="Ver Tiempos Muertos">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path
                                                    d="M12,22A10,10,0,1,1,22,12a1,1,0,0,0,2,0A12,12,0,1,0,12,24a1,1,0,0,0,0-2Z M12,6a1,1,0,0,0-1,1v4.586L8.293,14.293a1,1,0,1,0,1.414,1.414l3-3A1,1,0,0,0,13,12V7A1,1,0,0,0,12,6Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Detalle Procesamiento -->
                            <div class="modal fade" id="procesamiento{{ $sala->cod_tipo_planilla }}_{{ $sala->cod_sala }}"
                                tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalle Procesamiento - {{ $sala->nombre_sala }}
                                                ({{ $tipo_planilla }})</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="table-responsive">
                                                <table class="detail-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Nro Planilla</th>
                                                            <th>Empresa</th>
                                                            <th>Corte Inicial</th>
                                                            <th>Corte Final</th>
                                                            <th>Calibre</th>
                                                            <th>Calidad</th>
                                                            <th>Piezas</th>
                                                            <th>Kilos</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($procesamientoSala as $proceso)
                                                            <tr>
                                                                <td>{{ $proceso->cod_planilla }}</td>
                                                                <td>{{ $proceso->descripcion }}</td>
                                                                <td>{{ $proceso->corte_inicial }}</td>
                                                                <td>{{ $proceso->corte_final }}</td>
                                                                <td>{{ $proceso->calibre }}</td>
                                                                <td>{{ $proceso->calidad }}</td>
                                                                <td>{{ number_format($proceso->piezas, 0) }}</td>
                                                                <td>{{ number_format($proceso->kilos, 1) }} kg</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="6" class="text-end"><strong>Totales:</strong></td>
                                                            <td><strong>{{ number_format($procesamientoSala->sum('piezas'), 0) }}</strong>
                                                            </td>
                                                            <td><strong>{{ number_format($procesamientoSala->sum('kilos'), 1) }}
                                                                    kg</strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tiempos Muertos -->
                            <div class="modal fade" id="tiempos{{ $sala->cod_tipo_planilla }}_{{ $sala->cod_sala }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tiempos Muertos - {{ $sala->nombre_sala }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive">
                                                @php
                                                    $tiemposMuertosSala = collect($tiempos_muertos)->where('cod_sala', $sala->cod_sala);
                                                @endphp
                                                <table class="detail-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Motivo</th>
                                                            <th>Duración (min)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($tiemposMuertosSala as $tiempo)
                                                            <tr>
                                                                <td>{{ $tiempo->motivo }}</td>
                                                                <td>{{ number_format($tiempo->duracion_minutos, 0) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-end"><strong>Total:</strong></td>
                                                            <td><strong>{{ number_format($tiemposMuertosSala->sum('duracion_minutos'), 0) }}
                                                                    min</strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Sección de Empaque Premium -->
        <div class="tipo-planilla-section">
            <h3 class="tipo-planilla-title">Empaque </h3>
            <div class="salas-grid">
                <div class="card w-full max-w-2xl mb-[10px]">
                    <div class="card-header bg-primary/5 pb-2">
                        <div class="text-xl flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                Detalle
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <!-- Dotación Section -->
                        <div class="mb-4">
                            <h5 class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="text-primary me-2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Dotación
                            </h5>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label text-muted small mb-1">Real</label>
                                    <input type="number" class="form-control form-control-sm dotacion-input" min="0"
                                        value="0" data-area="empaque" onclick="this.select()"
                                        onchange="actualizarDotacionTotal(); calcularProductividadEmpaque()">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted small mb-1">Esperada</label>
                                    <input type="number" class="form-control form-control-sm dotacion-esperada-input"
                                        min="0" value="0" data-area="empaque" onclick="this.select()"
                                        onchange="actualizarDotacionTotal()">
                                </div>
                            </div>
                        </div>

                        <!-- Indicadores Section -->
                        <div class="mb-4">
                            <h5 class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="text-primary me-2">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                </svg>
                                Indicadores
                            </h5>
                            <div class="row">
                                <div class="col-4 mb-3">
                                    <label class="form-label text-muted small mb-1">Horas Trabajadas</label>
                                    <div class="d-flex">
                                        <input type="number"
                                            class="form-control form-control-sm me-1 horas-trabajadas-empaque" min="0"
                                            value="0" onclick="this.select()" data-area="empaque"
                                            onchange="calcularProductividadEmpaque()">
                                        <span class="mt-1 me-1">h</span>
                                        <input type="number"
                                            class="form-control form-control-sm me-1 minutos-trabajados-empaque" min="0"
                                            max="59" value="0" onclick="this.select()" data-area="empaque"
                                            onchange="calcularProductividadEmpaque()">
                                        <span class="mt-1">m</span>
                                    </div>
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-label text-muted small mb-1">Tiempo Muerto</label>
                                    <div class="d-flex">
                                        <input type="number" class="form-control form-control-sm me-1 horas-muertas-empaque"
                                            min="0" value="0" onclick="this.select()" data-area="empaque"
                                            onchange="calcularProductividadEmpaque()">
                                        <span class="mt-1 me-1">h</span>
                                        <input type="number"
                                            class="form-control form-control-sm me-1 minutos-muertos-empaque" min="0"
                                            max="59" value="0" onclick="this.select()" data-area="empaque"
                                            onchange="calcularProductividadEmpaque()">
                                        <span class="mt-1">m</span>
                                    </div>
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-label text-muted small mb-1">Productividad</label>
                                    <div class="d-flex align-items-center">
                                        <div class="fw-bold productividad-empaque">0.0</div>
                                        <span class="ms-2">pzs/pers/hr</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(count($empaque_premium) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr class="text-muted small">
                                            <th class="border-0">Producto</th>
                                            <th class="border-0">Empresa</th>
                                            <th class="border-0 text-end">Lotes</th>
                                            <th class="border-0 text-end">Kilos</th>
                                            <th class="border-0 text-end">Piezas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @foreach($empaque_premium as $premium)
                                            <tr>
                                                <td class="border-0">{{ $premium->Producto }}</td>
                                                <td class="border-0">{{ $premium->Empresa }}</td>
                                                <td class="border-0 text-end">{{ $premium->Cantidad_Lotes }}</td>
                                                <td class="border-0 text-end">
                                                    {{ number_format($premium->Total_Kilos, 1, ',', '.') }}
                                                </td>
                                                <td class="border-0 text-end">
                                                    {{ number_format($premium->Total_Piezas, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="small fw-bold">
                                        <tr>
                                            <td class="border-0" colspan="3"></td>
                                            <td class="border-0 text-end">
                                                {{ number_format(collect($empaque_premium)->sum('Total_Kilos'), 1, ',', '.') }}
                                            </td>
                                            <td class="border-0 text-end">
                                                {{ number_format(collect($empaque_premium)->sum('Total_Piezas'), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info py-2 small">
                                No hay datos de empaque premium para este turno.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 comentarios-card">

            <div class="p-3">
                <h4>Comentarios del Turno</h4>
                <textarea class="form-control" id="comentarios_turno" name="comentarios_turno" rows="3"
                    placeholder="Ingrese sus comentarios aquí..."></textarea>
            </div>
        </div>

        <div class="text-center mb-4">
            <button class="btn btn-detail btn-lg px-5" onclick="guardarInforme()">
                Guardar y Confirmar Informe
            </button>
        </div>

    </div>


@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function calcularProductividadEmpaque() {
            try {
                // Obtener valores de los inputs
                const dotacionReal = parseInt(document.querySelector('.dotacion-input[data-area="empaque"]')?.value) || 0;
                const horasTrabajadas = parseInt(document.querySelector('.horas-trabajadas-empaque')?.value) || 0;
                const minutosTrabajados = parseInt(document.querySelector('.minutos-trabajados-empaque')?.value) || 0;

                // Calcular horas trabajadas en formato decimal
                const horasTrabajadasDecimal = horasTrabajadas + (minutosTrabajados / 60);

                // Obtener el total de piezas de la tabla de empaque premium
                const totalPiezas = Array.from(document.querySelectorAll('table tbody tr td:nth-child(5)'))
                    .reduce((sum, cell) => {
                        const text = cell.textContent.trim().replace(/[^\d.-]/g, '');
                        return sum + (parseInt(text) || 0);
                    }, 0);

                // Calcular productividad (piezas / (dotación * horas trabajadas))
                let productividad = 0;
                if (dotacionReal > 0 && horasTrabajadasDecimal > 0) {
                    productividad = totalPiezas / (dotacionReal * horasTrabajadasDecimal);
                }

                // Actualizar el texto de productividad
                const productividadElement = document.querySelector('.productividad-empaque');
                if (productividadElement) {
                    productividadElement.textContent = productividad.toFixed(1);
                }

                return productividad;
            } catch (error) {
                console.error('Error al calcular productividad de empaque:', error);
                return 0;
            }
        }

        function actualizarDotacionTotal() {
            try {
                let totalReal = 0;
                let totalEsperada = 0;
                const inputsReal = document.querySelectorAll('.dotacion-input');
                const inputsEsperada = document.querySelectorAll('.dotacion-esperada-input');

                inputsReal.forEach(input => {
                    totalReal += parseInt(input.value) || 0;

                    // Actualizar productividad para cada sala o área
                    if (input.dataset.area === 'empaque') {
                        // Cálculo específico para empaque
                        const dotacion = parseInt(input.value) || 0;
                        const totalPiezas = Array.from(document.querySelectorAll('.detail-table tbody tr'))
                            .reduce((sum, row) => sum + (parseInt(row.cells[4]?.textContent.replace(/[^\d.-]/g, '')) || 0), 0);

                    } else {
                        // Cálculo existente para salas
                        const salaCard = input.closest('.sala-card');
                        if (salaCard) {
                            const dotacion = parseInt(input.value) || 0;
                            const horasTrabajadas = parseFloat(salaCard.querySelector('[data-horas-trabajadas]')?.dataset?.horasTrabajadas) || 0;
                            const tipoPlanilla = salaCard.dataset.tipoPlanilla;

                            let productividad = 0;
                            if (horasTrabajadas > 0 && dotacion > 0) {
                                if (tipoPlanilla === 'Porciones') {
                                    const kilosRecepcion = parseFloat(salaCard.querySelector('[data-kilos-recepcion]')?.textContent?.replace(/[^\d.-]/g, '')) || 0;
                                    productividad = kilosRecepcion / (horasTrabajadas * dotacion);
                                } else {
                                    const piezasRecepcion = parseInt(salaCard.querySelector('[data-piezas-recepcion]')?.textContent?.replace(/[^\d.-]/g, '')) || 0;
                                    productividad = piezasRecepcion / (horasTrabajadas * dotacion);
                                }
                            }

                            const productividadElement = salaCard.querySelector('.productividad-valor');
                            if (productividadElement) {
                                const unidad = tipoPlanilla === 'Porciones' ? '<label class="form-label text-muted small mb-1">kg/pers/hr</label>' : '<label class="form-label text-muted small mb-1">pzs/pers/hr</label>';
                                productividadElement.innerHTML = productividad > 0 ? productividad.toFixed(1) + unidad : '-' + unidad;
                            }
                        }
                    }
                });

                inputsEsperada.forEach(input => {
                    totalEsperada += parseInt(input.value) || 0;
                });

                const dotacionTotalElement = document.getElementById('dotacion-total');
                const dotacionEsperadaTotalElement = document.getElementById('dotacion-esperada-total');
                const porcentajeAusentismoElement = document.getElementById('porcentaje-ausentismo');

                if (dotacionTotalElement) {
                    dotacionTotalElement.textContent = totalReal > 0 ? totalReal : '-';
                }
                if (dotacionEsperadaTotalElement) {
                    dotacionEsperadaTotalElement.textContent = totalEsperada > 0 ? totalEsperada : '-';
                }
                if (porcentajeAusentismoElement && totalEsperada > 0) {
                    const ausentismo = ((totalEsperada - totalReal) / totalEsperada * 100).toFixed(1);
                    porcentajeAusentismoElement.textContent = ausentismo + '%';
                }
            } catch (error) {
                console.error('Error al actualizar dotación:', error);
            }
        }

        function validarDatos() {
            try {
                const salas = document.querySelectorAll('.sala-card[data-sala-id]');
                if (!salas.length) {
                    toastr.error('No se encontraron salas para procesar');
                    return false;
                }

                const comentarios = document.getElementById('comentarios_turno')?.value;
                if (!comentarios?.trim()) {
                    toastr.error('Debe ingresar comentarios del turno');
                    return false;
                }

                // Validar dotación de empaque
                const dotacionRealEmpaque = parseInt(document.querySelector('.dotacion-input[data-area="empaque"]')?.value) || 0;
                if (dotacionRealEmpaque === 0) {
                    toastr.error('Debe ingresar la dotación real de empaque');
                    return false;
                }

                // Validar horas trabajadas de empaque
                const horasTrabajadasEmpaque = parseInt(document.querySelector('.horas-trabajadas-empaque')?.value) || 0;
                const minutosTrabajadasEmpaque = parseInt(document.querySelector('.minutos-trabajados-empaque')?.value) || 0;
                if (horasTrabajadasEmpaque === 0 && minutosTrabajadasEmpaque === 0) {
                    toastr.error('Debe ingresar las horas trabajadas de empaque');
                    return false;
                }

                // Validar que todas las salas tengan datos
                let datosValidos = true;
                salas.forEach((sala) => {
                    const dotacionReal = parseInt(sala.querySelector('.dotacion-input')?.value) || 0;
                    const dotacionEsperada = parseInt(sala.querySelector('.dotacion-esperada-input')?.value) || 0;

                    if (dotacionReal === 0 || dotacionEsperada === 0) {
                        toastr.error(`La sala ${sala.querySelector('.sala-title')?.textContent} debe tener dotación real y esperada`);
                        datosValidos = false;
                    }
                });

                return datosValidos;
            } catch (error) {
                console.error('Error en validación:', error);
                toastr.error('Error al validar los datos');
                return false;
            }
        }

        async function guardarInforme() {
            if (!validarDatos()) return;

            try {
                // Validar si existe informe para esta fecha y turno
                const response = await fetch('{{ route("informes.validar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        fecha: '{{ $fecha }}',
                        turno: {{ $turno }}
                                                                                                        })
                });

                const data = await response.json();

                if (data.existe) {
                    Swal.fire({
                        title: 'Informe Existente',
                        text: 'Ya existe un informe para esta fecha y turno. No es posible crear otro.',
                        icon: 'warning',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Confirmar Informe?',
                    text: "¿Está seguro de guardar el informe del turno?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        enviarDatos();
                    }
                });
            } catch (error) {
                console.error('Error:', error);
                toastr.error('Error al validar el informe: ' + error.message);
            }
        }

        function enviarDatos() {
            try {
                const salas = document.querySelectorAll('.sala-card[data-sala-id]');
                console.log(parseInt(document.querySelector('.dotacion-input[data-area="empaque"]')?.value) || 0 + 'd_real_empaque');
                console.log(parseInt(document.querySelector('.dotacion-esperada-input[data-area="empaque"]')?.value) || 0 + 'd_esperada_empaque');
                // Calcular horas trabajadas y tiempo muerto para empaque
                const horasTrabajadasEmpaque = parseInt(document.querySelector('.horas-trabajadas-empaque')?.value) || 0;
                const minutosTrabajadasEmpaque = parseInt(document.querySelector('.minutos-trabajados-empaque')?.value) || 0;
                const horasMuertasEmpaque = parseInt(document.querySelector('.horas-muertas-empaque')?.value) || 0;
                const minutosMuertosEmpaque = parseInt(document.querySelector('.minutos-muertos-empaque')?.value) || 0;

                // Convertir a decimal (horas.minutos)
                const horasTrabajadasEmpaqueDecimal = horasTrabajadasEmpaque + (minutosTrabajadasEmpaque / 60);

                // Convertir a minutos totales para tiempo muerto
                const tiempoMuertoEmpaqueMinutos = (horasMuertasEmpaque * 60) + minutosMuertosEmpaque;

                // Obtener la productividad calculada
                const productividadEmpaque = calcularProductividadEmpaque();

                const datosInforme = {
                    fecha_turno: '{{ $fecha }}',
                    cod_turno: {{ $turno }},
                    cod_jefe_turno: '{{ $informe->jefe_turno ?? "" }}',
                    comentarios: document.getElementById('comentarios_turno')?.value,
                    d_real_empaque: parseInt(document.querySelector('.dotacion-input[data-area="empaque"]')?.value) || 0,
                    d_esperada_empaque: parseInt(document.querySelector('.dotacion-esperada-input[data-area="empaque"]')?.value) || 0,
                    horas_trabajadas_empaque: horasTrabajadasEmpaqueDecimal,
                    tiempo_muerto_empaque: tiempoMuertoEmpaqueMinutos,
                    productividad_empaque: productividadEmpaque,
                    salas: Array.from(salas).map(sala => {
                        // Obtener los valores numéricos limpiando el formato
                        const getNumericValue = (selector) => {
                            const element = sala.querySelector(selector);
                            if (!element) return 0;
                            // Limpiar el texto de formato (comas, kg, etc)
                            const text = element.textContent.replace(/,/g, '').replace(/[^\d.-]/g, '');
                            return parseFloat(text) || 0;
                        };

                        // Obtener el valor de premium porcentaje
                        const premiumElement = sala.querySelector('.premium-valor');
                        const premiumValue = premiumElement ?
                            parseFloat(premiumElement.textContent.replace('%', '')) || 0 : 0;

                        // Buscar específicamente los kilos premium en la sección de Recepción PST
                        const recepcionPstSection = Array.from(sala.querySelectorAll('p.text-muted.small.mb-1'))
                            .find(p => p.textContent.trim() === 'Kilos Premium')
                            ?.nextElementSibling;
                        const kilosPremium = recepcionPstSection ?
                            parseFloat(recepcionPstSection.textContent.replace(/,/g, '').replace(/[^\d.-]/g, '')) || 0 : 0;

                        return {
                            cod_sala: sala.dataset.salaId,
                            nombre_sala: sala.dataset.salaNombre,
                            tipo_planilla: sala.dataset.tipoPlanilla,
                            dotacion_real: parseInt(sala.querySelector('.dotacion-input')?.value) || 0,
                            dotacion_esperada: parseInt(sala.querySelector('.dotacion-esperada-input')?.value) || 0,
                            kilos_entrega: getNumericValue('[data-kilos-entrega]'),
                            kilos_recepcion: getNumericValue('[data-kilos-recepcion]'),
                            kilos_premium: kilosPremium,
                            piezas_entrega: getNumericValue('[data-piezas-entrega]'),
                            piezas_recepcion: getNumericValue('[data-piezas-recepcion]'),
                            horas_trabajadas: parseFloat(sala.querySelector('[data-horas-trabajadas]')?.dataset?.horasTrabajadas) || 0,
                            tiempo_muerto_minutos: parseInt(sala.querySelector('[data-tiempo-muerto]')?.dataset?.tiempoMuerto) || 0,
                            rendimiento: parseFloat(sala.querySelector('.rendimiento-valor')?.textContent?.replace('%', '')) || 0,
                            productividad: parseFloat(sala.querySelector('.productividad-valor')?.textContent?.replace(/[^\d.-]/g, '')) || 0,
                            premium: premiumValue
                        };
                    })
                };

                // Mostrar datos en consola de forma organizada
                console.group('Datos a enviar al servidor:');
                console.log('Fecha:', datosInforme.fecha_turno);
                console.log('Turno:', datosInforme.cod_turno);
                console.log('Jefe de Turno:', datosInforme.cod_jefe_turno);
                console.log('Comentarios:', datosInforme.comentarios);

                console.group('Datos por Sala:');
                datosInforme.salas.forEach(sala => {
                    console.group(`Sala: ${sala.nombre_sala}`);
                    console.log('Código:', sala.cod_sala);
                    console.log('Tipo Planilla:', sala.tipo_planilla);
                    console.log('Dotación Real:', sala.dotacion_real);
                    console.log('Dotación Esperada:', sala.dotacion_esperada);
                    console.log('Kilos Entrega:', sala.kilos_entrega.toFixed(1));
                    console.log('Kilos Recepción:', sala.kilos_recepcion.toFixed(1));
                    console.log('Kilos Premium:', sala.kilos_premium.toFixed(1));
                    console.log('Premium (%):', sala.premium.toFixed(1));
                    console.log('Piezas Entrega:', sala.piezas_entrega);
                    console.log('Piezas Recepción:', sala.piezas_recepcion);
                    console.log('Horas Trabajadas:', sala.horas_trabajadas);
                    console.log('Tiempo Muerto (min):', sala.tiempo_muerto_minutos);
                    console.log('Rendimiento (%):', sala.rendimiento);
                    console.log('Productividad:', sala.productividad);
                    console.groupEnd();
                });
                console.groupEnd();
                console.groupEnd();

                // Continuar con el envío de datos
                fetch('{{ route("informes.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(datosInforme)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            toastr.success('Informe guardado correctamente');
                            setTimeout(() => {
                                window.location.href = '/pst2/public/mis-informes';
                            }, 1500);
                        } else {
                            throw new Error(data.message || 'Error al guardar el informe');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Error al guardar el informe: ' + error.message);
                    });
            } catch (error) {
                console.error('Error al preparar los datos:', error);
                toastr.error('Error al preparar los datos: ' + error.message);
            }
        }

        // Inicializar cuando el DOM esté cargado
        document.addEventListener('DOMContentLoaded', function () {
            actualizarDotacionTotal();
            calcularProductividadEmpaque();
        });
    </script>
@endsection