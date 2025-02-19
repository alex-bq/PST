@extends('layouts.main-iframe')

@section('title', 'Detalle del Informe')

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

        .sala-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

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
            width: 50%;
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
        <h3 class="page-subtitle">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} - {{ $informe->NomTurno }} -
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
                <div class="turno-info-item">
                    <strong>Total Kilos Entrega</strong>
                    <span>{{ $informe->total_kilos_entrega > 0 ? number_format($informe->total_kilos_entrega, 1) . ' kg' : '-' }}</span>
                </div>
                <div class="turno-info-item">
                    <strong>Total Kilos Recepción</strong>
                    <span>{{ $informe->total_kilos_recepcion > 0 ? number_format($informe->total_kilos_recepcion, 1) . ' kg' : '-' }}</span>
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
                    @foreach($informacion_sala as $sala)
                            <div class="card mb-4 sala-card" data-sala-id="{{ $sala->cod_sala }}">
                                <h4 class="card-header sala-title">{{ $sala->nombre_sala }}</h4>

                                <div class="card-body">
                                    <!-- Sección de dotación en su propia fila -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-4 align-items-center">
                                                    <strong>Dotación:</strong>
                                                    <div class="d-flex gap-3">
                                                        <div class="dotacion-group">
                                                            <div class="dotacion-label">Dotación Real:</div>
                                                            <div class="dotacion-value">
                                                                <span class="dotacion-input"
                                                                    data-dotacion-real="{{ $sala->dotacion_real }}">
                                                                    {{ number_format($sala->dotacion_real, 0) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="dotacion-group">
                                                            <div class="dotacion-label">Dotación Esperada:</div>
                                                            <div class="dotacion-value">
                                                                <span class="dotacion-esperada-input"
                                                                    data-dotacion-esperada="{{ $sala->dotacion_esperada }}">
                                                                    {{ number_format($sala->dotacion_esperada, 0) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grid de información de la sala -->
                                    <div class="sala-info">
                                        <div class="sala-info-item">
                                            <strong>Kilos Entrega</strong>
                                            <span data-kilos-entrega="{{ $sala->kilos_entrega }}">
                                                {{ number_format($sala->kilos_entrega, 1) }} kg
                                            </span>
                                        </div>
                                        <div class="sala-info-item">
                                            <strong>Kilos Recepción</strong>
                                            <span data-kilos-recepcion="{{ $sala->kilos_recepcion }}">
                                                {{ number_format($sala->kilos_recepcion, 1) }} kg
                                            </span>
                                        </div>
                                        <div class="sala-info-item">
                                            <strong>Rendimiento</strong>
                                            <span class="rendimiento-valor">0.0%</span>
                                        </div>
                                        <div class="sala-info-item">
                                            <strong>Horas Trabajadas</strong>
                                            @php
                                                $horasEnteras = floor($sala->horas_trabajadas);
                                                $minutos = round(($sala->horas_trabajadas - $horasEnteras) * 60);
                                            @endphp
                                            <span data-horas-trabajadas="{{ $sala->horas_trabajadas }}">
                                                {{ $horasEnteras }}h {{ $minutos }}m
                                            </span>
                                        </div>
                                        <div class="sala-info-item">
                                            <strong>Tiempo Muerto</strong>
                                            @php
                                                $minutosTiempoMuerto = collect($tiempos_muertos)
                                                    ->where('cod_sala', $sala->cod_sala)
                                                    ->sum('duracion_minutos');
                                                $horasTM = floor($minutosTiempoMuerto / 60);
                                                $minutosTM = $minutosTiempoMuerto % 60;
                                            @endphp
                                            <span data-tiempo-muerto="{{ $minutosTiempoMuerto }}">{{ $horasTM }}h
                                                {{ $minutosTM }}m</span>
                                        </div>
                                        <div class="sala-info-item">
                                            <strong>Productividad</strong>
                                            <span class="productividad-valor">-</span>
                                        </div>
                                    </div>
                                    <div class="sala-actions" style="margin-top: 1rem;">
                                        <button class="btn btn-primary btn-detail" data-bs-toggle="modal"
                                            data-bs-target="#procesamiento_{{ $sala->cod_sala }}" data-bs-tooltip="tooltip"
                                            title="Ver Detalle Procesamiento">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path
                                                    d="M8 6.00067L21 6.00139M8 12.0007L21 12.0015M8 18.0007L21 18.0015M3.5 6H3.51M3.5 12H3.51M3.5 18H3.51M4 6C4 6.27614 3.77614 6.5 3.5 6.5C3.22386 6.5 3 6.27614 3 6C3 5.72386 3.22386 5.5 3.5 5.5C3.77614 5.5 4 5.72386 4 6ZM4 12C4 12.2761 3.77614 12.5 3.5 12.5C3.22386 12.5 3 12.2761 3 12C3 11.7239 3.22386 11.5 3.5 11.5C3.77614 11.5 4 11.7239 4 12ZM4 18C4 18.2761 3.77614 18.5 3.5 18.5C3.22386 18.5 3 18.2761 3 18C3 17.7239 3.22386 17.5 3.5 17.5C3.77614 17.5 4 17.7239 4 18Z" />
                                            </svg>
                                        </button>
                                        <button class="btn btn-primary btn-detail ms-2" data-bs-toggle="modal"
                                            data-bs-target="#tiempos_{{ $sala->cod_sala }}" data-bs-tooltip="tooltip"
                                            title="Ver Tiempos Muertos">
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
                            <div class="modal fade" id="procesamiento_{{ $sala->cod_sala }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalle Procesamiento - {{ $sala->nombre_sala }}
                                                ({{ $tipo_planilla }})</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @php
                                                $procesamientoSala = collect($detalle_procesamiento)
                                                    ->where('cod_sala', $sala->cod_sala);
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
                            <div class="modal fade" id="tiempos_{{ $sala->cod_sala }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tiempos Muertos - {{ $sala->nombre_sala }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive">
                                                @php
                                                    $tiemposMuertosSala = collect($tiempos_muertos)
                                                        ->where('cod_sala', $sala->cod_sala);
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

        <!-- Comentarios del Turno -->
        <div class="card mb-4 comentarios-card">
            <div class="p-3">
                <h4>Comentarios del Turno</h4>
                <div class="form-control" style="min-height: 80px; background-color: #f8f9fa;">
                    {{ $informe->comentarios ?? 'Sin comentarios' }}
                </div>
            </div>
        </div>



    </div>


@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Calcular todos los indicadores al cargar la página
            actualizarDotacionTotal();

            // Actualizar indicadores para cada sala
            document.querySelectorAll('.sala-card').forEach(salaCard => {
                actualizarIndicadoresSala(salaCard);
            });
        });

        function actualizarDotacionTotal() {
            try {
                let totalReal = 0;
                let totalEsperada = 0;
                const spanReal = document.querySelectorAll('.dotacion-input');
                const spanEsperada = document.querySelectorAll('.dotacion-esperada-input');

                spanReal.forEach(span => {
                    totalReal += parseInt(span.dataset.dotacionReal) || 0;
                });

                spanEsperada.forEach(span => {
                    totalEsperada += parseInt(span.dataset.dotacionEsperada) || 0;
                });

                // Actualizar totales
                document.getElementById('dotacion-total').textContent = totalReal;
                document.getElementById('dotacion-esperada-total').textContent = totalEsperada;

                // Calcular y mostrar porcentaje de ausentismo
                if (totalEsperada > 0) {
                    const ausentismo = ((totalEsperada - totalReal) / totalEsperada * 100).toFixed(1);
                    document.getElementById('porcentaje-ausentismo').textContent = ausentismo + '%';
                }
            } catch (error) {
                console.error('Error al actualizar dotación:', error);
            }
        }

        function actualizarIndicadoresSala(salaCard) {
            try {
                const dotacionReal = parseInt(salaCard.querySelector('.dotacion-input').dataset.dotacionReal) || 0;
                const kilosRecepcion = parseFloat(salaCard.querySelector('[data-kilos-recepcion]').dataset.kilosRecepcion) || 0;
                const kilosEntrega = parseFloat(salaCard.querySelector('[data-kilos-entrega]').dataset.kilosEntrega) || 0;
                const horasTrabajadas = parseFloat(salaCard.querySelector('[data-horas-trabajadas]').dataset.horasTrabajadas) || 0;

                // Calcular rendimiento
                const rendimiento = kilosEntrega > 0 ? (kilosRecepcion / kilosEntrega * 100) : 0;
                salaCard.querySelector('.rendimiento-valor').textContent = rendimiento.toFixed(1) + '%';

                // Calcular productividad
                const productividad = (horasTrabajadas > 0 && dotacionReal > 0)
                    ? kilosRecepcion / (horasTrabajadas * dotacionReal)
                    : 0;
                salaCard.querySelector('.productividad-valor').textContent =
                    productividad > 0 ? productividad.toFixed(1) + ' kg/pers/hora' : '-';
            } catch (error) {
                console.error('Error al actualizar indicadores de sala:', error);
            }
        }

        function mostrarDatosEnConsola() {
            try {
                const salas = document.querySelectorAll('.sala-card');
                const datosTurno = {
                    resumen: {
                        dotacionTotal: document.getElementById('dotacion-total')?.textContent || '-',
                        dotacionEsperadaTotal: document.getElementById('dotacion-esperada-total')?.textContent || '-',
                        ausentismo: document.getElementById('porcentaje-ausentismo')?.textContent || '-',
                        totalKilosEntrega: document.querySelector('.turno-info-grid [data-kilos-entrega]')?.textContent?.trim() || '-',
                        totalKilosRecepcion: document.querySelector('.turno-info-grid [data-kilos-recepcion]')?.textContent?.trim() || '-'
                    },
                    salas: []
                };

                salas.forEach(sala => {
                    try {
                        const salaData = {
                            nombreSala: sala.querySelector('.sala-title')?.textContent || '-',
                            codSala: sala.dataset.salaId || '-',
                            dotacionReal: sala.querySelector('.dotacion-input')?.value || '0',
                            dotacionEsperada: sala.querySelector('.dotacion-esperada-input')?.value || '0',
                            kilosEntrega: sala.querySelector('[data-kilos-entrega]')?.dataset?.kilosEntrega || '0',
                            kilosRecepcion: sala.querySelector('[data-kilos-recepcion]')?.dataset?.kilosRecepcion || '0',
                            horasTrabajadas: sala.querySelector('[data-horas-trabajadas]')?.dataset?.horasTrabajadas || '0',
                            rendimiento: sala.querySelector('.rendimiento-valor')?.textContent || '-',
                            productividad: sala.querySelector('.productividad-valor')?.textContent || '-'
                        };
                        datosTurno.salas.push(salaData);
                    } catch (salaError) {
                        console.error('Error al procesar sala:', salaError);
                    }
                });

                const comentarios = document.getElementById('comentarios_turno')?.value || '';
                datosTurno.comentarios = comentarios;

                console.log('=== DATOS DEL TURNO ===');
                console.log('Resumen:', datosTurno.resumen);
                console.log('Comentarios:', datosTurno.comentarios);
                console.log('Datos por Sala:', datosTurno.salas);

                return datosTurno;
            } catch (error) {
                console.error('Error al mostrar datos:', error);
                return null;
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


    </script>
@endsection