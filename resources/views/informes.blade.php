@extends('layouts.main-iframe')

@section('title', 'Informes de Producción')

@section('styles')
<style>
    .card-icon {
        color: #1C1D22;
    }

    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
        min-height: 320px;
        width: 100%;
    }

    .card .card-body {
        padding: 2rem;
    }

    .card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .card-icon svg {
        width: 32px;
        height: 32px;
    }

    .card-title {
        font-size: 1.5rem;
    }

    .card-stats {
        margin-top: 2rem;
    }

    .card-stats .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        margin-bottom: 8px;
    }

    .card-stats .text-muted {
        font-size: 1rem;
        flex: 1;
    }

    .card-stats .fw-medium {
        font-size: 1.1rem;
        margin-left: 16px;
        text-align: right;
    }

    .text-primary {
        color: #0d6efd !important;
    }

    .text-success {
        color: #198754 !important;
    }

    .custom-input {
        height: 56px;
    }

    .btn-search {
        background-color: #000120;
        color: white;
    }

    .btn-search:hover {
        background-color: #14142a;
        color: white;
    }

    @media (min-width: 768px) {
        .col {
            padding: 0 20px;
        }
    }

    @media (max-width: 767px) {
        .card {
            margin-bottom: 1.5rem;
            min-height: 280px;
        }
    }

    .card-clickable {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .card-clickable:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .card-clickable:active {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <!-- Título -->
            <h1 class="text-center fw-bold mb-4" style="font-size: 32px;">Buscar Informes Diarios</h1>

            <!-- Formulario de búsqueda -->
            <div class="row justify-content-center mb-4">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Seleccionar Día</label>
                        <input type="date" id="fecha" class="form-control custom-input"
                            placeholder="Ejemplo: 2023-10-01">
                    </div>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-search flex-grow-1" id="btnBuscar">Buscar</button>
                        <button class="btn btn-light flex-grow-1" id="btnLimpiar">Limpiar</button>
                    </div>
                </div>
            </div>

            <!-- Contenedor para los informes -->
            <div class="row row-cols-1 row-cols-md-2 g-4 px-4" id="contenedorInformes">
                <!-- Los informes se cargarán dinámicamente aquí -->
            </div>

            <!-- Mensaje de no resultados -->
            <div id="mensajeNoResultados" class="text-center d-none">
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">No se encontraron informes</h4>
                    <p class="mb-0">No hay informes disponibles para la fecha seleccionada.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnBuscar = document.getElementById('btnBuscar');
        const btnLimpiar = document.getElementById('btnLimpiar');
        const inputFecha = document.getElementById('fecha');
        const contenedorInformes = document.getElementById('contenedorInformes');
        const mensajeNoResultados = document.getElementById('mensajeNoResultados');
        const overlay = document.getElementById('iframeLoadingOverlay');

        // Función para mostrar/ocultar el overlay de carga
        const toggleLoading = (show) => {
            if (overlay) {
                overlay.style.display = show ? 'flex' : 'none';
            }
        };

        // Función para buscar informes
        btnBuscar.addEventListener('click', async function () {
            const fecha = inputFecha.value;
            if (!fecha) {
                toastr.warning('Por favor, seleccione una fecha');
                return;
            }

            try {
                // Mostrar overlay de carga
                toggleLoading(true);

                // Ocultar mensaje de no resultados
                mensajeNoResultados.classList.add('d-none');

                // Usar la URL generada por Laravel
                const url = "{{ route('informes.diarios', ['fecha' => ':fecha']) }}".replace(':fecha', fecha);

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const informes = await response.json();

                // Limpiar contenedor
                contenedorInformes.innerHTML = '';

                if (!informes || informes.length === 0) {
                    // Mostrar mensaje de no resultados
                    mensajeNoResultados.classList.remove('d-none');
                    return;
                }

                // Ordenar informes por turno
                informes.sort((a, b) => a.orden_turno - b.orden_turno);

                // Generar HTML para cada informe
                informes.forEach(informe => {
                    // Función para formatear números con separadores de miles y decimales específicos
                    const formatNumber = (number, decimals = 0) => {
                        return new Intl.NumberFormat('es-CL', {
                            minimumFractionDigits: decimals,
                            maximumFractionDigits: decimals
                        }).format(number);
                    };

                    // Formatear los valores
                    const productividad = formatNumber(informe.productividad_promedio, 2);
                    const dotacion = formatNumber(informe.dotacion_promedio, 0);
                    const kilos = formatNumber(informe.total_kilos_entrega, 1);

                    // Función para obtener el número de turno
                    const getTurnoNumero = (turnoTexto) => {
                        if (turnoTexto.includes('Día')) return '1';
                        if (turnoTexto.includes('Tarde')) return '2';
                        if (turnoTexto.includes('Noche')) return '3';
                        return '1'; // valor por defecto
                    };

                    const turnoNumero = getTurnoNumero(informe.turno);

                    const html = `
                        <div class="col">
                            <div class="card h-100 border card-clickable" 
                                 onclick="verDetalleTurno('${fecha}', '${turnoNumero}')"
                                 role="button">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="card-icon me-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                                viewBox="0 0 256 256">
                                                <path d="M232,208a8,8,0,0,1-8,8H32a8,8,0,0,1-8-8V48a8,8,0,0,1,16,0v94.37L90.73,98a8,8,0,0,1,10.07-.38l58.81,44.11L218.73,90a8,8,0,1,1,10.54,12l-64,56a8,8,0,0,1-10.07.38L96.39,114.29,40,163.63V200H224A8,8,0,0,1,232,208Z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="card-title fw-bold mb-0">${informe.turno}</h5>
                                    </div>
                                    
                                    <div class="card-stats">
                                        <div class="stat-item mb-2">
                                            <span class="text-muted">Jefe de Turno:</span>
                                            <span class="fw-medium">${informe.jefe_turno}</span>
                                        </div>
                                        <div class="stat-item mb-2">
                                            <span class="text-muted">Dotación:</span>
                                            <span class="fw-medium">${dotacion} personas</span>
                                        </div>
                                        <div class="stat-item mb-2">
                                            <span class="text-muted">Productividad:</span>
                                            <span class="fw-medium text-primary">${productividad} kg/pers/hr</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="text-muted">Total Kilos:</span>
                                            <span class="fw-medium text-success">${kilos} kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    contenedorInformes.innerHTML += html;
                });

            } catch (error) {
                console.error('Error:', error);
                toastr.error('Error al cargar los informes');
                mensajeNoResultados.classList.remove('d-none');
            } finally {
                // Ocultar overlay de carga
                toggleLoading(false);
            }
        });

        // Función para limpiar
        btnLimpiar.addEventListener('click', function () {
            inputFecha.value = '';
            contenedorInformes.innerHTML = '';
            mensajeNoResultados.classList.add('d-none');
        });

        // También podemos buscar al presionar Enter en el input
        inputFecha.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                btnBuscar.click();
            }
        });
    });

    // Función para ver el detalle del turno
    function verDetalleTurno(fecha, turno) {
        // Usar la URL base de Laravel
        const baseUrl = "{{ url('/') }}";
        window.location.href = `${baseUrl}/informes/detalle/${fecha}/${turno}`;
    }
</script>
@endsection