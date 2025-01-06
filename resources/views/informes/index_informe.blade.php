@extends('layouts.main-iframe')

@section('title', 'Informes de Producción')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/informes.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>Informes de Producción</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Filtros</h5>
        </div>
        <div class="card-body">
            <form id="filtroForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha Inicio:</label>
                            <input type="date" class="form-control" name="fecha_inicio"
                                value="{{ date('Y-m-d', strtotime('-7 days')) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha Fin:</label>
                            <input type="date" class="form-control" name="fecha_fin" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Turno:</label>
                            <select class="form-control" name="turno">
                                <option value="">Todos</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->codTurno }}">{{ $turno->NomTurno }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Supervisor:</label>
                            <select class="form-control" name="supervisor">
                                <option value="">Todos</option>
                                @foreach($supervisores as $supervisor)
                                    <option value="{{ $supervisor->cod_usuario }}">{{ $supervisor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Resultados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaInformes">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Turno</th>
                                    <th>Dotación</th>
                                    <th>Productividad</th>
                                    <th>Rendimiento</th>
                                    <th>Kilos Producidos</th>
                                    <th>Tiempo Muerto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="detalleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del Informe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- El contenido se cargará dinámicamente -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        function formatNumber(value) {
            if (value === null || value === undefined) return '0.00';
            const num = parseFloat(value);
            return isNaN(num) ? '0.00' : num.toFixed(2);
        }

        function formatDate(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            return date.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        }

        function cargarInformes() {
            $('#tablaInformes tbody').html('<tr><td colspan="8" class="text-center">Cargando...</td></tr>');

            $.ajax({
                url: '{{ route("informes.filtrar") }}',
                type: 'POST',
                data: $('#filtroForm').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log('Respuesta del servidor:', response); // Debug

                    if (response.error) {
                        toastr.error('Error: ' + response.error);
                        return;
                    }

                    if (!response.informes || response.informes.length === 0) {
                        $('#tablaInformes tbody').html('<tr><td colspan="8" class="text-center">No se encontraron registros para los filtros seleccionados</td></tr>');
                        return;
                    }

                    let tbody = '';
                    response.informes.forEach(function (informe) {
                        tbody += `
                            <tr>
                                <td>${formatDate(informe.fecha)}</td>
                                <td>${informe.nombre_turno || informe.cod_turno}</td>
                                <td class="text-right">${informe.total_dotacion || 0}</td>
                                <td class="text-right">${formatNumber(informe.promedio_productividad)}</td>
                                <td class="text-right">${formatNumber(informe.promedio_rendimiento)}%</td>
                                <td class="text-right">${formatNumber(informe.total_kilos_entrega)}</td>
                                <td class="text-right">${informe.total_minutos_muertos || 0} min</td>
                                <td>
                                    <button class="btn btn-sm btn-info ver-detalle" 
                                        data-fecha="${informe.fecha}"
                                        data-turno="${informe.cod_turno}">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#tablaInformes tbody').html(tbody);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                    toastr.error('Error al cargar los informes: ' + error);
                    $('#tablaInformes tbody').html('<tr><td colspan="8" class="text-center text-danger">Error al cargar los datos</td></tr>');
                }
            });
        }

        $('#filtroForm').on('submit', function (e) {
            e.preventDefault();
            cargarInformes();
        });

        $(document).on('click', '.ver-detalle', function () {
            const fecha = $(this).data('fecha');
            const turno = $(this).data('turno');

            $.ajax({
                url: '{{ route("informes.detalle") }}',
                type: 'GET',
                data: {
                    fecha: fecha,
                    turno: turno
                },
                success: function (response) {
                    if (response.error) {
                        toastr.error('Error: ' + response.error);
                        return;
                    }

                    const detalle = response.detalle;
                    const desglose = response.desglose;
                    const tiemposMuertos = response.tiempos_muertos;

                    let desgloseHtml = '<table class="table table-sm">';
                    desgloseHtml += '<thead><tr><th>Corte</th><th>Calidad</th><th>Registros</th><th>Piezas</th><th>Kilos</th></tr></thead><tbody>';

                    desglose.forEach(function (item) {
                        desgloseHtml += `
                            <tr>
                                <td>${item.nombre_corte || 'No especificado'}</td>
                                <td>${item.nombre_calidad || 'No especificado'}</td>
                                <td class="text-right">${item.total_registros}</td>
                                <td class="text-right">${item.total_piezas}</td>
                                <td class="text-right">${formatNumber(item.total_kilos)}</td>
                            </tr>
                        `;
                    });
                    desgloseHtml += '</tbody></table>';

                    let tiemposHtml = '<table class="table table-sm">';
                    tiemposHtml += '<thead><tr><th>Descripción</th><th>Duración (min)</th></tr></thead><tbody>';

                    tiemposMuertos.forEach(function (tiempo) {
                        tiemposHtml += `
                            <tr>
                                <td>${tiempo.descripcion}</td>
                                <td class="text-right">${tiempo.duracion_minutos}</td>
                            </tr>
                        `;
                    });
                    tiemposHtml += '</tbody></table>';

                    if (response.planillas && response.planillas.length > 0) {
                        let planillasHtml = '<h6 class="mt-4">Planillas Relacionadas</h6>';
                        planillasHtml += '<table class="table table-sm">';
                        planillasHtml += '<thead><tr>';
                        planillasHtml += '<th>Código</th>';
                        planillasHtml += '<th>Dotación</th>';
                        planillasHtml += '<th>Productividad</th>';
                        planillasHtml += '<th>Rendimiento</th>';
                        planillasHtml += '<th>Kilos Entrega</th>';
                        planillasHtml += '<th>Kilos Recepción</th>';
                        planillasHtml += '<th>Acciones</th>';
                        planillasHtml += '</tr></thead><tbody>';

                        response.planillas.forEach(function (planilla) {
                            planillasHtml += `
                                <tr>
                                    <td>${planilla.cod_planilla}</td>
                                    <td>${planilla.dotacion || 0}</td>
                                    <td>${formatNumber(planilla.productividad) || 0}</td>
                                    <td>${formatNumber(planilla.rendimiento) || 0}%</td>
                                    <td>${formatNumber(planilla.kilos_entrega) || 0}</td>
                                    <td>${formatNumber(planilla.kilos_recepcion) || 0}</td>
                                    <td>
                                        <a href="/pst/public/planilla/${planilla.cod_planilla}" 
                                           class="btn btn-sm btn-primary" 
                                           target="_blank">
                                            <i class="fas fa-eye"></i> Ver Planilla
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });

                        planillasHtml += '</tbody></table>';
                        $('#detalleModal .modal-body').append(planillasHtml);
                    }

                    $('#detalleModal .modal-body').html(`
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Información General</h6>
                                <p><strong>Fecha:</strong> ${detalle.fecha}</p>
                                <p><strong>Turno:</strong> ${detalle.nombre_turno || 'No especificado'}</p>
                                <p><strong>Supervisor:</strong> ${detalle.nombre_supervisor || 'No especificado'}</p>
                                <p><strong>Dotación:</strong> ${detalle.total_dotacion}</p>
                                <p><strong>Productividad:</strong> ${formatNumber(detalle.promedio_productividad)}</p>
                                <p><strong>Rendimiento:</strong> ${formatNumber(detalle.promedio_rendimiento)}%</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Producción</h6>
                                <p><strong>Kilos Entrega:</strong> ${formatNumber(detalle.total_kilos_entrega)}</p>
                                <p><strong>Kilos Recepción:</strong> ${formatNumber(detalle.total_kilos_recepcion)}</p>
                                <p><strong>Tiempo Muerto Total:</strong> ${detalle.total_minutos_muertos || 0} min</p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Desglose de Producción</h6>
                                ${desgloseHtml}
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Tiempos Muertos</h6>
                                ${tiemposHtml}
                            </div>
                        </div>
                    `);

                    $('#detalleModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                    toastr.error('Error al cargar los detalles: ' + error);
                }
            });
        });

        // Cargar informes al iniciar
        cargarInformes();
    });
</script>
@endsection