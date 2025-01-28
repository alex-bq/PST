@extends('layouts.main-iframe')

@section('title', 'Detalle del Turno')

@section('styles')
<style>
    .back-button {
        background-color: #000120;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .back-button:hover {
        background-color: #14142a;
        color: white;
    }

    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .detail-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #1C1D22;
    }

    .detail-table {
        width: 100%;
        margin-bottom: 1rem;
    }

    .detail-table th {
        background-color: #f8f9fa;
        padding: 0.75rem;
        font-weight: 600;
    }

    .detail-table td {
        padding: 0.75rem;
        border-top: 1px solid #dee2e6;
    }

    .accordion-button:not(.collapsed) {
        background-color: #000120;
        color: white;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0, 0, 0, 0.125);
    }

    .accordion-item {
        margin-bottom: 1rem;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .accordion-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .sala-stats {
        color: #6c757d;
        font-size: 0.9rem;
        margin-left: 1rem;
    }

    .sala-stats span {
        margin-right: 1.5rem;
    }

    .sala-stats strong {
        color: #000120;
    }

    .page-title {
        color: #000120;
        margin-bottom: 2rem;
        text-align: center;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .salas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .sala-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .sala-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #000120;
        margin-bottom: 1rem;
    }

    .sala-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .sala-info-item {
        margin-bottom: 0.5rem;
    }

    .sala-info-item strong {
        display: block;
        color: #000120;
        font-size: 0.9rem;
    }

    .sala-info-item span {
        color: #6c757d;
    }

    .accordion-section {
        margin-top: 1rem;
    }

    .accordion-section-title {
        color: #000120;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .turno-info {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .turno-info-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: center;
    }

    .turno-info-item {
        text-align: center;
    }

    .turno-info-item strong {
        display: block;
        color: #000120;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .turno-info-item span {
        color: #6c757d;
        font-size: 1.1rem;
    }

    .sala-header {
        margin-bottom: 1.5rem;
    }

    .sala-stat-item {
        font-size: 0.9rem;
    }

    .modal-header {
        background-color: #000120;
        color: white;
    }

    .modal-title {
        color: white;
    }

    .btn-detail {
        background-color: #000120;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        margin-right: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-detail:hover {
        background-color: #000140;
        transform: translateY(-2px);
    }

    .sala-actions {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <a href="/pst/public/informes" class="back-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
        </svg>
        Volver a Informes
    </a>

    <h2 class="page-title">Detalle del Turno - {{ $informe->turno }}</h2>
    <p class="page-subtitle">{{ $informe->fecha }} - Turno {{ $informe->turno }}</p>
    <!-- Aquí irá la información del informe diario -->

    <!-- Información del Turno -->
    <div class="turno-info">
        <div class="turno-info-grid">
            <div class="turno-info-item">
                <strong>Fecha</strong>
                <span>{{ \Carbon\Carbon::parse($informe->fecha)->format('d/m/Y') }}</span>
            </div>
            <div class="turno-info-item">
                <strong>Jefe de Turno</strong>
                <span>{{ $informe->jefe_turno }}</span>
            </div>
            <div class="turno-info-item">
                <strong>Dotación Total</strong>
                <span>{{ number_format($informe->dotacion_promedio, 0) }} personas</span>
            </div>
            <div class="turno-info-item">
                <strong>Productividad Promedio</strong>
                <span>{{ number_format($informe->productividad_promedio, 2) }} kg/pers/hr</span>
            </div>
            <div class="turno-info-item">
                <strong>Kilos Entrega Total</strong>
                <span>{{ number_format($informe->total_kilos_entrega, 1) }} kg</span>
            </div>
            <div class="turno-info-item">
                <strong>Kilos Recepción Total</strong>
                <span>{{ number_format($informe->total_kilos_recepcion, 1) }} kg</span>
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
                        <div class="sala-card">
                            <h4 class="sala-title">{{ $sala->nombre_sala }}</h4>
                            <div class="sala-info">
                                <div class="sala-info-item">
                                    <strong>Dotación</strong>
                                    <span>{{ number_format($sala->dotacion_promedio, 0) }}</span>
                                </div>
                                <div class="sala-info-item">
                                    <strong>Productividad</strong>
                                    <span>{{ number_format($sala->productividad_promedio, 2) }} kg/pers/hr</span>
                                </div>
                                <div class="sala-info-item">
                                    <strong>Rendimiento</strong>
                                    <span>{{ number_format($sala->rendimiento_promedio, 2) }}%</span>
                                </div>
                                <div class="sala-info-item">
                                    <strong>Kilos Entrega</strong>
                                    <span>{{ number_format($sala->kilos_entrega_total, 1) }} kg</span>
                                </div>
                                <div class="sala-info-item">
                                    <strong>Kilos Recepción</strong>
                                    <span>{{ number_format($sala->kilos_recepcion_total, 1) }} kg</span>
                                </div>
                            </div>

                            <div class="sala-actions">
                                <button type="button" class="btn-detail" data-bs-toggle="modal"
                                    data-bs-target="#procesamiento{{ $sala->cod_tipo_planilla }}_{{ $sala->cod_sala }}">
                                    Ver Detalle Procesamiento
                                </button>
                                <button type="button" class="btn-detail" data-bs-toggle="modal"
                                    data-bs-target="#tiempos{{ $sala->cod_tipo_planilla }}_{{ $sala->cod_sala }}">
                                    Ver Tiempos Muertos
                                </button>
                            </div>
                        </div>

                        <!-- Modal Detalle Procesamiento -->
                        <div class="modal fade" id="procesamiento{{ $sala->cod_tipo_planilla }}_{{ $sala->cod_sala }}"
                            tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Detalle Procesamiento - {{ $sala->nombre_sala }} ({{ $tipo_planilla }})
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $procesamientoSala = collect($detalle_procesamiento)
                                                ->where('cod_sala', $sala->cod_sala)
                                                ->where('cod_tipo_planilla', $sala->cod_tipo_planilla);
                                        @endphp
                                        <div class="table-responsive">
                                            <table class="detail-table">
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
                                                    @foreach($procesamientoSala as $proceso)
                                                        <tr>
                                                            <td>{{ $proceso->corte_inicial }}</td>
                                                            <td>{{ $proceso->corte_final }}</td>
                                                            <td>{{ $proceso->destino }}</td>
                                                            <td>{{ $proceso->calibre }}</td>
                                                            <td>{{ $proceso->calidad }}</td>
                                                            <td>{{ number_format($proceso->piezas, 0) }}</td>
                                                            <td>{{ number_format($proceso->kilos, 1) }} kg</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5" class="text-end"><strong>Totales:</strong></td>
                                                        <td><strong>{{ number_format($procesamientoSala->sum('piezas'), 0) }}</strong>
                                                        </td>
                                                        <td><strong>{{ number_format($procesamientoSala->sum('kilos'), 1) }}
                                                                kg</strong>
                                                        </td>
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
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
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
</div>
@endsection

@section('scripts')
<script>
    // Aquí irá el JavaScript necesario para la interactividad
</script>
@endsection