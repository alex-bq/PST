@extends('layouts.main-iframe')

@section('title', 'Dashboard Productividad')

@section('styles')
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.css" rel="stylesheet">
@endsection

@section('content')
    <div class="flex min-h-screen w-full flex-col">
        <!-- Navbar -->
        <div class="border-b bg-white">
            <div class="flex h-16 items-center px-4">
                <h2 class="text-lg font-semibold">Dashboard de Productividad</h2>
                <div class="ml-auto flex items-center space-x-4">
                    <!-- Selector de Fecha -->
                    <div class="relative">
                        <input type="date" id="fecha"
                            class="w-[240px] px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer"
                            value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Selector de Línea -->
                    <select id="tipo_planilla"
                        class="w-[180px] px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer bg-white">
                        <option value="Filete" selected>Filete</option>
                        <option value="Porciones">Porciones</option>
                        <option value="HG">HG</option>
                        <option value="Empaque">Empaque</option>
                    </select>

                    <!-- Selector de Turno (movido aquí) -->
                    <div class="flex items-center space-x-2">
                        <label for="turnoSelector" class="text-sm font-medium text-gray-700">Turno:</label>
                        <select id="turnoSelector"
                            class="w-32 px-2 py-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="todos">Todos</option>
                            <option value="Día">Día</option>
                            <option value="Tarde">Tarde</option>
                            <option value="Noche">Noche</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de KPIs -->
        <div class="p-6">
            <!-- Título para los indicadores semanales -->
            <h3 class="text-base font-medium text-gray-700 mb-3">Indicadores Semanales</h3>
            <div class="grid grid-cols-5 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Dotación vs Esperada</h4>
                    <p class="text-2xl font-bold text-purple-600" id="kpiDotacion">--%</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Ausentismo Promedio</h4>
                    <p class="text-2xl font-bold text-indigo-600" id="kpiAusentismo">--%</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Tiempo Efectivo</h4>
                    <p class="text-2xl font-bold text-green-600" id="kpiTiempoEfectivo">--%</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Rendimiento General</h4>
                    <p class="text-2xl font-bold text-orange-600" id="kpiRendimientoGeneral">--%</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Rendimiento Premium</h4>
                    <p class="text-2xl font-bold text-blue-600" id="kpiRendimientoPremium">--%</p>
                </div>
            </div>

            <!-- Título para los gráficos -->
            <h3 class="text-base font-medium text-gray-700 mb-3">Gráficos por Turno: <span id="turnoTitulo">Todos</span>
            </h3>
            <!-- Gráficos principales -->
            <div class="grid grid-cols-6 gap-6" id="graficos-produccion">
                <!-- Gráfico de Productividad (ocupa 4/6) -->
                <div class="col-span-4 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold hidden">Productividad por Turno</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <label for="metaProductividad" class="text-sm font-medium text-gray-700">Meta:</label>
                                <input type="number" id="metaProductividad"
                                    class="w-24 px-2 py-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="10000" step="1">
                                <span class="text-sm text-gray-500" id="unidadMeta">kg</span>
                            </div>
                        </div>
                    </div>
                    <div id="productividadChart" class="h-[400px]"></div>
                </div>

                <!-- Gráfico de Rendimiento (ocupa 2/6) -->
                <div class="col-span-2 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold hidden">Rendimiento Semanal</h3>
                    </div>
                    <div id="rendimientoChart" class="h-[400px]"></div>
                </div>

                <!-- Gráfico de Dotación -->
                <div class="col-span-2 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold hidden">Dotación Semanal</h3>
                    </div>
                    <div id="dotacionChart" class="h-[300px]"></div>
                </div>

                <!-- Gráfico de Tiempos Muertos -->
                <div class="col-span-2 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold hidden">Distribución de Tiempo Diario</h3>
                    </div>
                    <div id="tiemposMuertosChart" class="h-[300px]"></div>
                </div>

                <!-- Gráfico de Tiempos Muertos por Departamento -->
                <div class="col-span-2 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold hidden">Tiempos Muertos por Departamento</h3>
                    </div>
                    <div id="tiemposMuertosSemanalChart" class="h-[300px]"></div>
                </div>
            </div>

            <!-- Gráficos de Empaque (ocultos por defecto) -->
            <div class="grid grid-cols-6 gap-6 hidden" id="graficos-empaque">
                <!-- Gráfico de Productividad Empaque -->
                <div class="col-span-6 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Productividad de Empaque</h3>
                    </div>
                    <div id="productividadEmpaqueChart" class="w-full h-[400px]"></div>
                </div>

                <!-- Gráfico de Dotación Empaque -->
                <div class="col-span-3 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Dotación de Empaque</h3>
                    </div>
                    <div id="dotacionEmpaqueChart" class="w-full h-[400px]"></div>
                </div>

                <!-- Gráfico de Horas Trabajadas y Tiempo Muerto -->
                <div class="col-span-3 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Horas Trabajadas y Tiempo Muerto</h3>
                    </div>
                    <div id="horasTrabajadasEmpaqueChart" class="w-full h-[400px]"></div>
                </div>
                <!-- Gráfico de Kilos por Producto -->
                <div class="col-span-3 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Kilos por Producto</h3>
                    </div>
                    <div id="kilosProductoChart" class="w-full h-[400px]"></div>
                </div>

                <!-- Gráfico de Piezas por Producto -->
                <div class="col-span-3 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Piezas por Producto</h3>
                    </div>
                    <div id="piezasProductoChart" class="w-full h-[400px]"></div>
                </div>

                <!-- Gráfico de Distribución por Empresa -->
                <div class="col-span-3 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Distribución por Empresa</h3>
                    </div>
                    <div id="distribucionEmpresaChart" class="w-full h-[400px]"></div>
                </div>

                <!-- Gráfico de Cantidad de Lotes -->
                <div class="col-span-3 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Cantidad de Lotes por Día</h3>
                    </div>
                    <div id="lotesChart" class="w-full h-[400px]"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fechaInput = document.getElementById('fecha');
            const tipoPlanillaSelect = document.getElementById('tipo_planilla');
            const metaProductividadInput = document.getElementById('metaProductividad');
            const turnoSelector = document.getElementById('turnoSelector');
            const unidadMeta = document.getElementById('unidadMeta');

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('es-CL', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            function formatDateForComparison(dateString) {
                // Si la fecha ya viene en formato ISO (YYYY-MM-DD), asegurarnos de que se interprete correctamente
                if (typeof dateString === 'string' && dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
                    return dateString; // Ya está en el formato correcto YYYY-MM-DD
                }

                // Para fechas que vienen de la API, pueden incluir información de tiempo
                let date;
                if (typeof dateString === 'string') {
                    // Intentar extraer componentes de fecha si viene en otro formato
                    const parts = dateString.split(/[-T ]/);
                    if (parts.length >= 3) {
                        // Usar los componentes de la fecha para crear una fecha UTC
                        const year = parseInt(parts[0]);
                        const month = parseInt(parts[1]) - 1; // Meses en JS son 0-indexed
                        const day = parseInt(parts[2]);

                        // Crear fecha UTC para evitar ajustes de zona horaria
                        date = new Date(Date.UTC(year, month, day));
                    } else {
                        // Si no podemos parsear, usar el constructor estándar
                        date = new Date(dateString);
                    }
                } else {
                    // Si no es string, asumir que es un objeto Date
                    date = new Date(dateString);
                }

                // Formatear como YYYY-MM-DD usando UTC para evitar problemas de zona horaria
                return date.getUTCFullYear() + '-' +
                    String(date.getUTCMonth() + 1).padStart(2, '0') + '-' +
                    String(date.getUTCDate()).padStart(2, '0');
            }

            function formatDateForDisplay(dateString) {
                if (!dateString) return '';

                // Si la fecha ya viene en formato ISO (YYYY-MM-DD), convertirla a objeto Date
                let date;
                if (typeof dateString === 'string' && dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
                    const parts = dateString.split('-');
                    date = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                } else {
                    date = new Date(dateString);
                }

                // Formatear como DD/MM
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                return `${day}/${month}`;
            }

            // Función auxiliar para depurar fechas
            function debugDate(dateString, label) {
                // Crear fecha sin ajustes de zona horaria si es posible
                let date;
                let utcDate;

                if (typeof dateString === 'string') {
                    const parts = dateString.split(/[-T ]/);
                    if (parts.length >= 3) {
                        const year = parseInt(parts[0]);
                        const month = parseInt(parts[1]) - 1; // Meses en JS son 0-11
                        const day = parseInt(parts[2]);
                        date = new Date(year, month, day);
                        utcDate = new Date(Date.UTC(year, month, day));
                    } else {
                        date = new Date(dateString);
                        utcDate = new Date(dateString);
                    }
                } else {
                    date = new Date(dateString);
                    utcDate = new Date(dateString);
                }

                console.log(`${label}:`, {
                    original: dateString,
                    date: date.toString(),
                    utcDate: utcDate.toString(),
                    year: date.getFullYear(),
                    month: date.getMonth() + 1,
                    day: date.getDate(),
                    utcDay: utcDate.getUTCDate(),
                    dayOfWeek: date.getDay(),
                    formatted: formatDateForComparison(dateString),
                    localString: date.toLocaleDateString(),
                    isoString: date.toISOString()
                });
                return date;
            }

            function formatearTiempo(minutos) {
                const horas = Math.floor(minutos / 60);
                const minutosRestantes = Math.floor(minutos % 60);
                if (horas > 0) {
                    return `${horas}h ${minutosRestantes}m`;
                }
                return `${minutosRestantes}m`;
            }

            function cargarDatos() {
                const fecha = fechaInput.value;
                console.log('Fecha enviada a la API:', fecha);
                debugDate(fecha, 'Fecha seleccionada para API');
                const tipoPlanilla = tipoPlanillaSelect.value;

                // Limpiar gráficos existentes
                const contenedores = [
                    "#productividadChart",
                    "#rendimientoChart",
                    "#dotacionChart",
                    "#tiemposDiariosChart",
                    "#tiemposMuertosChart",
                    "#tiemposMuertosSemanalChart",
                    "#kilosProductoChart",
                    "#piezasProductoChart",
                    "#distribucionEmpresaChart",
                    "#lotesChart",
                    "#productividadEmpaqueChart",
                    "#dotacionEmpaqueChart",
                    "#horasTrabajadasEmpaqueChart"
                ];

                // Destruir los gráficos existentes si existen
                if (window.charts) {
                    for (let chartId in window.charts) {
                        if (window.charts[chartId]) {
                            window.charts[chartId].destroy();
                            window.charts[chartId] = null;
                        }
                    }
                } else {
                    window.charts = {};
                }

                contenedores.forEach(selector => {
                    const contenedor = document.querySelector(selector);
                    if (contenedor) {
                        contenedor.innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">Cargando datos...</p></div>';
                    }
                });

                // Mensaje de no datos disponibles
                const mensajeNoDatos = `
                                                                                                <div class="flex flex-col items-center justify-center p-6 text-gray-500">
                                                                                                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                                                                                    </svg>
                                                                                                    <p class="text-lg font-semibold">No hay datos disponibles</p>
                                                                                                    <p class="text-sm">Para la fecha ${new Date(fecha).toLocaleDateString()} y línea ${tipoPlanilla}</p>
                                                                                                </div>
                                                                                            `;

                // Función para limpiar y mostrar mensaje
                const mostrarMensajeNoDatos = () => {
                    const contenedores = [
                        "#productividadChart",
                        "#rendimientoChart",
                        "#dotacionChart",
                        "#tiemposMuertosChart",
                        "#tiemposMuertosSemanalChart",
                        "#kilosProductoChart",
                        "#piezasProductoChart",
                        "#distribucionEmpresaChart",
                        "#lotesChart",
                        "#productividadEmpaqueChart",
                        "#dotacionEmpaqueChart",
                        "#horasTrabajadasEmpaqueChart"
                    ];

                    contenedores.forEach(selector => {
                        const container = document.querySelector(selector);
                        if (container) {
                            container.innerHTML = mensajeNoDatos;
                        }
                    });

                    // Limpiar KPIs
                    document.getElementById('kpiAusentismo').textContent = '--';
                    document.getElementById('kpiTiempoEfectivo').textContent = '--';
                    document.getElementById('kpiRendimientoPremium').textContent = '--';
                    document.getElementById('kpiRendimientoGeneral').textContent = '--';
                    document.getElementById('kpiDotacion').textContent = '--';
                };

                fetch(`/pst2/public/api/dashboard-data?fecha=${fecha}&tipo_planilla=${tipoPlanilla}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos recibidos de la API:', data);

                        // Depurar fechas de los datos recibidos
                        if (data.produccion && data.produccion.length > 0) {
                            console.log('Ejemplo de fecha de la API:', data.produccion[0].fecha_turno);
                            console.log('Fecha formateada para comparación:', formatDateForComparison(data.produccion[0].fecha_turno));
                        }

                        // Verificar el tipo de planilla y los datos correspondientes
                        if (tipoPlanilla === 'Empaque') {
                            if (!data.empaque || data.empaque.length === 0) {
                                console.log('No hay datos de empaque');
                                document.querySelectorAll("#kilosProductoChart, #piezasProductoChart, #distribucionEmpresaChart, #lotesChart")
                                    .forEach(el => {
                                        el.innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                                    });
                            } else {
                                actualizarGraficosProductos(data.empaque);
                            }
                            return;
                        }

                        // Verificar si hay datos de producción
                        if (!data.produccion || data.produccion.length === 0) {
                            console.log('No hay datos de producción');
                            mostrarMensajeNoDatos();
                            return;
                        }

                        // Depurar fechas de los datos recibidos
                        data.produccion.forEach(item => {
                            debugDate(item.fecha_turno, `Fecha turno (${item.turno_nombre})`);
                        });

                        // Si hay datos, actualizar gráficos
                        actualizarGraficos(data);

                        // Actualizar gráfico de tiempos muertos solo si hay datos
                        if (data.tiempos_muertos && data.tiempos_muertos.length > 0) {
                            // Filtrar por turno si es necesario
                            let tiemposMuertosFiltrados = data.tiempos_muertos;
                            const turnoSeleccionado = turnoSelector.value;

                            if (turnoSeleccionado !== 'todos') {
                                tiemposMuertosFiltrados = data.tiempos_muertos.filter(item => item.turno_nombre === turnoSeleccionado);
                                console.log('Tiempos muertos filtrados por turno:', turnoSeleccionado, tiemposMuertosFiltrados);
                            }

                            actualizarGraficoTiemposMuertosSemanales(tiemposMuertosFiltrados, turnoSeleccionado);
                        } else {
                            document.querySelector("#tiemposMuertosSemanalChart").innerHTML = mensajeNoDatos;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        mostrarMensajeNoDatos();
                        // Opcional: Mostrar un mensaje de error más específico
                        alert('Error al cargar los datos. Por favor, intente nuevamente.');
                    });
            }

            function actualizarGraficos(data) {
                // Obtener la fecha seleccionada y calcular el inicio/fin de la semana
                // Crear fecha sin ajustes de zona horaria
                const fechaPartes = fechaInput.value.split('-');
                const year = parseInt(fechaPartes[0]);
                const month = parseInt(fechaPartes[1]) - 1; // Meses en JS son 0-11
                const day = parseInt(fechaPartes[2]);
                // Usar UTC para evitar problemas de zona horaria
                const fechaSeleccionada = new Date(Date.UTC(year, month, day));

                console.log('Fecha seleccionada (UTC):', fechaSeleccionada.toISOString());

                // Si es empaque, mostrar gráficos específicos y salir
                if (tipoPlanillaSelect.value === 'Empaque') {
                    mostrarDashboardEmpaque(data);
                    return;
                }

                // Calcular el inicio de la semana (lunes)
                // Usamos getUTCDay para mantener consistencia con UTC
                const diaSemana = fechaSeleccionada.getUTCDay(); // 0 = domingo, 1 = lunes, ...
                const diasHastaLunes = diaSemana === 0 ? 6 : diaSemana - 1; // Ajustar para que la semana comience el lunes
                const inicioSemana = new Date(fechaSeleccionada);
                inicioSemana.setUTCDate(fechaSeleccionada.getUTCDate() - diasHastaLunes);

                // Calcular el fin de la semana (domingo)
                const finSemana = new Date(inicioSemana);
                finSemana.setUTCDate(inicioSemana.getUTCDate() + 6);

                // Crear un array con los días de la semana
                const diasSemana = [];
                for (let i = 0; i < 7; i++) {
                    const fecha = new Date(inicioSemana);
                    fecha.setUTCDate(inicioSemana.getUTCDate() + i);

                    // Formatear la fecha manualmente para evitar problemas de zona horaria
                    const year = fecha.getUTCFullYear();
                    const month = String(fecha.getUTCMonth() + 1).padStart(2, '0');
                    const day = String(fecha.getUTCDate()).padStart(2, '0');
                    const fechaFormateada = `${year}-${month}-${day}`;

                    diasSemana.push(fechaFormateada); // Formato YYYY-MM-DD
                }

                // Depurar los días de la semana calculados
                console.log('Días de la semana calculados:', diasSemana);

                // Obtener los datos de la semana
                const datosSemana = data.produccion || [];

                // Filtrar por turno si es necesario
                const turnoSeleccionado = turnoSelector.value;
                let datosFiltrados = datosSemana;

                if (turnoSeleccionado !== 'todos') {
                    datosFiltrados = datosSemana.filter(item => item.turno_nombre === turnoSeleccionado);
                }

                // Si no hay datos, mostrar mensaje y salir
                if (!datosFiltrados || datosFiltrados.length === 0) {
                    console.log('No hay datos de producción');
                    document.querySelectorAll("#productividadChart, #rendimientoChart, #dotacionChart, #tiemposDiariosChart, #tiemposMuertosChart")
                        .forEach(el => {
                            el.innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                        });
                    return;
                }

                // Lista de turnos para el gráfico de productividad
                const turnos = ['Día', 'Tarde', 'Noche'];

                // Gráfico de Productividad
                let seriesProductividad;
                if (turnoSeleccionado === 'todos') {
                    seriesProductividad = turnos.map(turno => ({
                        name: `${turno}`,
                        type: 'bar',
                        data: diasSemana.map(dia => {
                            // Filtrar todos los registros del mismo día y turno
                            const turnoDataArray = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                const coincide = fechaTurnoFormateada === dia && row.turno_nombre === turno;

                                // Depurar la comparación de fechas (solo para algunos registros para no saturar la consola)
                                if (row.turno_nombre === 'Día') {
                                    console.log(`Comparando fechas: API=${row.fecha_turno}, Formateada=${fechaTurnoFormateada}, Día gráfico=${dia}, Coincide=${coincide}`);
                                }

                                return coincide;
                            });

                            // Si no hay datos para este día y turno, retornar null
                            if (turnoDataArray.length === 0) return null;

                            // Sumar todas las piezas o kilos de recepción
                            let total = 0;
                            turnoDataArray.forEach(turnoData => {
                                // Si es Porciones, usar kilos_recepcion, si no usar piezas_recepcion
                                if (tipoPlanillaSelect.value === 'Porciones') {
                                    total += Number(turnoData.kilos_recepcion || 0);
                                } else if (tipoPlanillaSelect.value === 'Empaque') {
                                    total += Number(turnoData.empaque || 0);
                                } else {
                                    total += Number(turnoData.piezas_recepcion || 0);
                                }
                            });

                            console.log(`Total para ${dia} - Turno ${turno}:`, {
                                registros: turnoDataArray.length,
                                total: total
                            });

                            return total;
                        })
                    }));
                } else {
                    seriesProductividad = [{
                        name: 'Productividad',
                        type: 'bar',
                        data: diasSemana.map(dia => {
                            // Filtrar todos los registros del mismo día y turno seleccionado
                            const turnoDataArray = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                const coincide = fechaTurnoFormateada === dia && row.turno_nombre === turnoSeleccionado;

                                // Depurar la comparación de fechas (solo para algunos registros para no saturar la consola)
                                if (row.turno_nombre === 'Día') {
                                    console.log(`Comparando fechas: API=${row.fecha_turno}, Formateada=${fechaTurnoFormateada}, Día gráfico=${dia}, Coincide=${coincide}`);
                                }

                                return coincide;
                            });

                            // Si no hay datos para este día y turno, retornar null
                            if (turnoDataArray.length === 0) return null;

                            // Sumar todas las piezas o kilos de recepción
                            let total = 0;
                            turnoDataArray.forEach(turnoData => {
                                // Si es Porciones, usar kilos_recepcion, si no usar piezas_recepcion
                                if (tipoPlanillaSelect.value === 'Porciones') {
                                    total += Number(turnoData.kilos_recepcion || 0);
                                } else if (tipoPlanillaSelect.value === 'Empaque') {
                                    total += Number(turnoData.empaque || 0);
                                } else {
                                    total += Number(turnoData.piezas_recepcion || 0);
                                }
                            });

                            console.log(`Total para ${dia} - Turno ${turnoSeleccionado}:`, {
                                registros: turnoDataArray.length,
                                total: total
                            });

                            return total;
                        })
                    }];
                }

                // Determinar la unidad de medida según el tipo de planilla
                const tipoPlanilla = tipoPlanillaSelect.value;
                const unidadMedida = (tipoPlanilla === 'Porciones') ? 'Kilos' : (tipoPlanilla === 'Empaque') ? 'Unidades' : 'Piezas';

                const optionsProductividad = {
                    series: seriesProductividad,
                    chart: {
                        height: 400,
                        type: 'bar',
                        stacked: false
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '70%'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            if (val === null || val === 0) return '';
                            return Math.round(val); // Redondear valores
                        },
                        style: {
                            fontSize: '12px',
                            colors: ['#1a237e']
                        }
                    },
                    colors: ['#008FFB', '#00E396', '#FEB019'],
                    xaxis: {
                        categories: diasSemana.map(dia => {
                            // Crear fecha usando UTC para evitar problemas de zona horaria
                            const parts = dia.split('-');
                            const year = parseInt(parts[0]);
                            const month = parseInt(parts[1]) - 1;
                            const day = parseInt(parts[2]);
                            const fecha = new Date(Date.UTC(year, month, day));

                            // Formatear como dd/mm usando UTC
                            return `${fecha.getUTCDate().toString().padStart(2, '0')}/${(fecha.getUTCMonth() + 1).toString().padStart(2, '0')}`;
                        }),
                        title: {
                            text: 'Fecha'
                        }
                    },
                    yaxis: {
                        title: {
                            text: `${unidadMedida} Recepción`
                        },
                        labels: {
                            formatter: function (val) {
                                return Math.round(val);
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                if (val === null) return 'Sin datos';
                                return Math.round(val) + ` ${unidadMedida}`;
                            }
                        }
                    },
                    annotations: {
                        yaxis: [{
                            y: parseFloat(metaProductividadInput.value),
                            borderColor: '#FF0000',
                            label: {
                                borderColor: '#FF0000',
                                style: {
                                    color: '#fff',
                                    background: '#FF0000',
                                    padding: {
                                        left: 10,
                                        right: 10
                                    }
                                },
                                text: `Meta: ${metaProductividadInput.value} ${tipoPlanillaSelect.value === 'Porciones' ? 'kg' : (tipoPlanillaSelect.value === 'Empaque') ? 'unidades' : 'pzs'}`
                            }
                        }]
                    }
                };

                // Calcular rendimientos totales por día
                const rendimientosPorDia = diasSemana.map(dia => {
                    // Obtener todos los turnos de ese día según el filtro seleccionado
                    const turnosDelDia = datosFiltrados.filter(row => {
                        const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                        return fechaTurnoFormateada === dia;
                    });

                    if (turnosDelDia.length === 0) return { rendimiento: null, rendimientoPremium: null };

                    // Sumar los kilos totales del día
                    const kilosEntregaTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.kilos_entrega || 0), 0);
                    const kilosRecepcionTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.kilos_recepcion || 0), 0);
                    const kilosPremiumTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.kilos_premium || 0), 0);

                    // Calcular rendimientos
                    const rendimientoTotal = kilosEntregaTotal > 0 ? (kilosRecepcionTotal * 100 / kilosEntregaTotal) : 0;
                    // Corregido: Premium sobre kilos de recepción
                    const rendimientoPremiumTotal = kilosRecepcionTotal > 0 ? (kilosPremiumTotal * 100 / kilosRecepcionTotal) : 0;

                    return {
                        rendimiento: rendimientoTotal,
                        rendimientoPremium: rendimientoPremiumTotal
                    };
                });

                // Series de rendimiento
                const seriesRendimiento = [
                    {
                        name: 'General',
                        type: 'line',
                        data: rendimientosPorDia.map(d => d.rendimiento)
                    },
                    {
                        name: 'Premium ',
                        type: 'line',
                        data: rendimientosPorDia.map(d => d.rendimientoPremium)
                    }
                ];

                const optionsRendimiento = {
                    series: seriesRendimiento,
                    chart: {
                        height: 400,
                        type: 'line',
                        animations: {
                            enabled: false
                        }
                    },
                    stroke: {
                        width: 4,
                        curve: 'smooth'
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            if (val === null || val === 0) return '';
                            return val.toFixed(2) + '%';
                        },
                        offsetY: -10
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    },
                    colors: ['#FF4560', '#775DD0'],
                    title: {
                        text: '',
                        align: 'left',
                        style: {
                            fontSize: '16px',
                            fontWeight: 'bold'
                        }
                    },
                    xaxis: {
                        categories: diasSemana.map(dia => {
                            // Crear fecha usando UTC para evitar problemas de zona horaria
                            const parts = dia.split('-');
                            const year = parseInt(parts[0]);
                            const month = parseInt(parts[1]) - 1;
                            const day = parseInt(parts[2]);
                            const fecha = new Date(Date.UTC(year, month, day));

                            // Formatear como dd/mm usando UTC
                            return `${fecha.getUTCDate().toString().padStart(2, '0')}/${(fecha.getUTCMonth() + 1).toString().padStart(2, '0')}`;
                        }),
                        title: {
                            text: 'Fecha'
                        }
                    },
                    yaxis: {
                        title: {
                            text: "Rendimiento (%)"
                        },
                        min: 0,
                        max: 100,
                        tickAmount: 10,
                        labels: {
                            formatter: function (val) {
                                return Math.round(val);
                            }
                        }
                    },
                    tooltip: {
                        shared: true,
                        y: {
                            formatter: function (val) {
                                if (val === null) return 'Sin datos';
                                return val.toFixed(2) + '%';
                            }
                        }
                    }
                };

                // Nuevo gráfico de dotación
                const optionsDotacion = {
                    series: [{
                        name: 'Dotación Real',
                        type: 'line',
                        data: diasSemana.map(dia => {
                            // Filtrar según el turno seleccionado
                            const turnosDelDia = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                return fechaTurnoFormateada === dia;
                            });

                            if (turnosDelDia.length === 0) return null;

                            // Sumar dotación real de todos los turnos del día
                            const dotacionRealTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.dotacion_real || 0), 0);
                            return dotacionRealTotal;
                        })
                    }, {
                        name: 'Dotación Esperada',
                        type: 'line',
                        data: diasSemana.map(dia => {
                            // Filtrar según el turno seleccionado
                            const turnosDelDia = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                return fechaTurnoFormateada === dia;
                            });

                            if (turnosDelDia.length === 0) return null;

                            // Sumar dotación esperada de todos los turnos del día
                            const dotacionEsperadaTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.dotacion_esperada || 0), 0);
                            return dotacionEsperadaTotal;
                        })
                    }],
                    chart: {
                        height: 300,
                        type: 'line',
                        toolbar: {
                            show: false
                        }
                    },
                    stroke: {
                        width: [3, 3],
                        curve: 'smooth'
                    },
                    colors: ['#818CF8', '#A78BFA'],
                    title: {
                        text: 'Dotación Real vs Esperada',
                        align: 'left',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold'
                        }
                    },
                    xaxis: {
                        categories: diasSemana.map(dia => {
                            // Crear fecha usando UTC para evitar problemas de zona horaria
                            const parts = dia.split('-');
                            const year = parseInt(parts[0]);
                            const month = parseInt(parts[1]) - 1;
                            const day = parseInt(parts[2]);
                            const fecha = new Date(Date.UTC(year, month, day));

                            // Formatear como dd/mm usando UTC
                            return `${fecha.getUTCDate().toString().padStart(2, '0')}/${(fecha.getUTCMonth() + 1).toString().padStart(2, '0')}`;
                        }),
                        title: {
                            text: 'Fecha'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Personas'
                        },
                        labels: {
                            formatter: function (val) {
                                return Math.round(val);
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    },
                    grid: {
                        padding: {
                            bottom: 20
                        }
                    }
                };

                // Gráfico de distribución de tiempo diario
                const optionsTiempoDiario = {
                    series: [{
                        name: 'Horas Trabajadas',
                        data: diasSemana.map(dia => {
                            // Filtrar según el turno seleccionado
                            const turnosDelDia = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                return fechaTurnoFormateada === dia;
                            });

                            if (turnosDelDia.length === 0) return null;

                            // Sumar horas trabajadas de todos los turnos del día (convertidas a minutos)
                            const horasTrabajadasTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.horas_trabajadas || 0) * 60, 0);
                            return horasTrabajadasTotal;
                        })
                    }, {
                        name: 'Tiempo Muerto',
                        data: diasSemana.map(dia => {
                            // Filtrar según el turno seleccionado
                            const turnosDelDia = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                return fechaTurnoFormateada === dia;
                            });

                            if (turnosDelDia.length === 0) return null;

                            // Sumar tiempo muerto de todos los turnos del día
                            const tiempoMuertoTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.tiempo_muerto_minutos || 0), 0);
                            return tiempoMuertoTotal;
                        })
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        stacked: true,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '70%',
                            dataLabels: {
                                position: 'center'
                            }
                        }
                    },
                    colors: ['#10B981', '#EF4444'], // Verde para horas trabajadas, Rojo para tiempo muerto
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return formatearTiempo(val);
                        },
                        style: {
                            fontSize: '12px',
                            colors: ['#fff'] // Color blanco para el texto de las etiquetas
                        }
                    },
                    xaxis: {
                        categories: diasSemana.map(dia => {
                            // Crear fecha usando UTC para evitar problemas de zona horaria
                            const parts = dia.split('-');
                            const year = parseInt(parts[0]);
                            const month = parseInt(parts[1]) - 1;
                            const day = parseInt(parts[2]);
                            const fecha = new Date(Date.UTC(year, month, day));

                            // Formatear como dd/mm usando UTC
                            return `${fecha.getUTCDate().toString().padStart(2, '0')}/${(fecha.getUTCMonth() + 1).toString().padStart(2, '0')}`;
                        })
                    },
                    yaxis: {
                        title: {
                            text: 'Minutos'
                        },
                        labels: {
                            formatter: function (val) {
                                return formatearTiempo(val);
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return formatearTiempo(val);
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    }
                };

                // Renderizar gráficos
                document.querySelector("#productividadChart").innerHTML = '';
                document.querySelector("#rendimientoChart").innerHTML = '';
                document.querySelector("#dotacionChart").innerHTML = '';
                document.querySelector("#tiemposMuertosChart").innerHTML = '';

                // Actualizar título de los gráficos según el turno seleccionado
                const tituloTurno = turnoSelector.value === 'todos' ? '' : ` - Turno ${turnoSelector.value}`;

                // Actualizar títulos de los gráficos
                optionsProductividad.title = {
                    text: `Productividad por Turno${tituloTurno}`,
                    align: 'left',
                    style: { fontSize: '14px', fontWeight: 'bold' }
                };

                optionsRendimiento.title = {
                    text: `Rendimiento Semanal${tituloTurno}`,
                    align: 'left',
                    style: { fontSize: '14px', fontWeight: 'bold' }
                };

                optionsDotacion.title = {
                    text: `Dotación Real vs Esperada${tituloTurno}`,
                    align: 'left',
                    style: { fontSize: '14px', fontWeight: 'bold' }
                };

                optionsTiempoDiario.title = {
                    text: `Distribución de Tiempo Diario${tituloTurno}`,
                    align: 'left',
                    style: { fontSize: '14px', fontWeight: 'bold' }
                };

                new ApexCharts(document.querySelector("#productividadChart"), optionsProductividad).render();
                new ApexCharts(document.querySelector("#rendimientoChart"), optionsRendimiento).render();
                new ApexCharts(document.querySelector("#dotacionChart"), optionsDotacion).render();
                new ApexCharts(document.querySelector("#tiemposMuertosChart"), optionsTiempoDiario).render();

                // Calcular KPIs
                const kpis = calcularKPIs(data);
                actualizarKPIs(kpis);

                // Actualizar gráfico de tiempos muertos por departamento
                if (data.tiempos_muertos && Array.isArray(data.tiempos_muertos)) {
                    actualizarGraficoTiemposMuertosSemanales(data.tiempos_muertos, turnoSelector.value);
                } else {
                    console.log('No hay datos de tiempos muertos disponibles');
                    document.querySelector("#tiemposMuertosSemanalChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos de tiempos muertos disponibles</p></div>';
                }
            }

            function actualizarGraficosProductos(data) {
                console.log('Iniciando actualización de gráficos de productos de empaque');
                console.log('Datos recibidos en actualizarGraficosProductos:', data);

                if (!data || data.length === 0) {
                    console.log('No hay datos de productos de empaque');
                    document.querySelector("#kilosProductoChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                    document.querySelector("#piezasProductoChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                    document.querySelector("#distribucionEmpresaChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                    document.querySelector("#lotesChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                    return;
                }

                // Limpiar los contenedores de gráficos existentes
                document.querySelectorAll("#kilosProductoChart, #piezasProductoChart, #distribucionEmpresaChart, #lotesChart")
                    .forEach(el => {
                        el.innerHTML = '';
                    });

                // Filtrar por turno seleccionado
                const turnoSeleccionado = turnoSelector.value;

                let datosFiltrados = data;

                if (turnoSeleccionado !== 'todos') {
                    datosFiltrados = data.filter(item => item.turno === turnoSeleccionado);
                }

                if (datosFiltrados.length === 0) {
                    console.log('No hay datos para el turno seleccionado');
                    document.querySelectorAll("#kilosProductoChart, #piezasProductoChart, #distribucionEmpresaChart, #lotesChart")
                        .forEach(el => {
                            el.innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles para el turno seleccionado</p></div>';
                        });
                    return;
                }

                // 1. Gráfico de Kilos por Producto
                const kilosPorProducto = {};
                datosFiltrados.forEach(item => {
                    if (!kilosPorProducto[item.Producto]) {
                        kilosPorProducto[item.Producto] = 0;
                    }
                    kilosPorProducto[item.Producto] += parseFloat(item.total_kilos);
                });

                const optionsKilosProducto = {
                    series: [{
                        name: 'Kilos',
                        data: Object.values(kilosPorProducto)
                    }],
                    chart: {
                        type: 'bar',
                        height: 400
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: Object.keys(kilosPorProducto),
                    },
                    yaxis: {
                        title: {
                            text: 'Kilos'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val.toFixed(1) + " kg";
                            }
                        }
                    }
                };

                const chartKilosProducto = new ApexCharts(document.querySelector("#kilosProductoChart"), optionsKilosProducto);
                chartKilosProducto.render();
                window.charts = window.charts || {};
                window.charts.kilosProducto = chartKilosProducto;

                // 2. Gráfico de Piezas por Producto
                const piezasPorProducto = {};
                datosFiltrados.forEach(item => {
                    if (!piezasPorProducto[item.Producto]) {
                        piezasPorProducto[item.Producto] = 0;
                    }
                    piezasPorProducto[item.Producto] += parseInt(item.total_piezas);
                });

                const optionsPiezasProducto = {
                    series: [{
                        name: 'Piezas',
                        data: Object.values(piezasPorProducto)
                    }],
                    chart: {
                        type: 'bar',
                        height: 400
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: Object.keys(piezasPorProducto),
                    },
                    yaxis: {
                        title: {
                            text: 'Piezas'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val.toFixed(0);
                            }
                        }
                    }
                };

                const chartPiezasProducto = new ApexCharts(document.querySelector("#piezasProductoChart"), optionsPiezasProducto);
                chartPiezasProducto.render();
                window.charts.piezasProducto = chartPiezasProducto;

                // 3. Gráfico de Distribución por Empresa
                const kilosPorEmpresa = {};
                datosFiltrados.forEach(item => {
                    if (!kilosPorEmpresa[item.Empresa]) {
                        kilosPorEmpresa[item.Empresa] = 0;
                    }
                    kilosPorEmpresa[item.Empresa] += parseFloat(item.total_kilos);
                });

                const optionsDistribucionEmpresa = {
                    series: Object.values(kilosPorEmpresa),
                    chart: {
                        type: 'pie',
                        height: 400
                    },
                    labels: Object.keys(kilosPorEmpresa),
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }],
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val.toFixed(1) + " kg";
                            }
                        }
                    }
                };

                const chartDistribucionEmpresa = new ApexCharts(document.querySelector("#distribucionEmpresaChart"), optionsDistribucionEmpresa);
                chartDistribucionEmpresa.render();
                window.charts.distribucionEmpresa = chartDistribucionEmpresa;

                // 4. Gráfico de Cantidad de Lotes
                const lotesPorDia = {};
                datosFiltrados.forEach(item => {
                    const fecha = item.fecha_turno;
                    if (!lotesPorDia[fecha]) {
                        lotesPorDia[fecha] = 0;
                    }
                    lotesPorDia[fecha] += parseInt(item.cantidad_lotes);
                });

                // Ordenar fechas
                const fechasOrdenadas = Object.keys(lotesPorDia).sort();

                const optionsLotes = {
                    series: [{ data: fechasOrdenadas.map(fecha => lotesPorDia[fecha]) }],
                    chart: {
                        height: 400,
                        type: 'line',
                    },
                    stroke: {
                        width: 3,
                        curve: 'smooth',
                        colors: ['#F59E0B']
                    },
                    xaxis: {
                        categories: fechasOrdenadas.map(fecha => {
                            // Formatear fecha como dd/mm
                            const partes = fecha.split('-');
                            return `${partes[2]}/${partes[1]}`;
                        }),
                    },
                    yaxis: {
                        title: {
                            text: 'Cantidad de Lotes'
                        }
                    },
                    markers: {
                        size: 5,
                    },
                    tooltip: {
                        x: {
                            formatter: function (val, opts) {
                                return fechasOrdenadas[opts.dataPointIndex];
                            }
                        }
                    }
                };

                const chartLotes = new ApexCharts(document.querySelector("#lotesChart"), optionsLotes);
                chartLotes.render();
                window.charts.lotes = chartLotes;
            }

            function mostrarDashboardEmpaque(data) {
                console.log('Mostrando dashboard de empaque');
                console.log('Datos recibidos:', data);

                // Mostrar gráficos de empaque
                const graficosEmpaque = document.getElementById('graficos-empaque');
                graficosEmpaque.classList.remove('hidden');

                // Ocultar gráficos de producción
                const graficosProduccion = document.getElementById('graficos-produccion');
                graficosProduccion.classList.add('hidden');

                // Actualizar gráficos de productividad de empaque
                actualizarGraficosEmpaque(data.productividad_empaque);

                // Actualizar gráficos de productos de empaque
                actualizarGraficosProductos(data.empaque);
            }

            function actualizarGraficosEmpaque(data) {
                // Verificar si hay datos de productividad de empaque
                if (!data || data.length === 0) {
                    console.log('No hay datos de productividad de empaque');
                    document.querySelector("#productividadEmpaqueChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                    document.querySelector("#dotacionEmpaqueChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                    document.querySelector("#horasTrabajadasEmpaqueChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                    return;
                }

                // Obtener los datos de productividad de empaque
                const datosProductividad = data;
                console.log('Datos de productividad de empaque:', datosProductividad);

                // Calcular el inicio y fin de la semana
                const fechaPartes = fechaInput.value.split('-');
                const year = parseInt(fechaPartes[0]);
                const month = parseInt(fechaPartes[1]) - 1; // Meses en JS son 0-11
                const day = parseInt(fechaPartes[2]);
                const fechaSeleccionada = new Date(Date.UTC(year, month, day));

                const diaSemana = fechaSeleccionada.getUTCDay(); // 0 = domingo, 1 = lunes, ...
                const diasHastaLunes = diaSemana === 0 ? 6 : diaSemana - 1; // Ajustar para que la semana comience el lunes
                const inicioSemana = new Date(fechaSeleccionada);
                inicioSemana.setUTCDate(fechaSeleccionada.getUTCDate() - diasHastaLunes);

                // Crear un array con los días de la semana
                const diasSemana = [];
                for (let i = 0; i < 7; i++) {
                    const fecha = new Date(inicioSemana);
                    fecha.setUTCDate(inicioSemana.getUTCDate() + i);
                    diasSemana.push(formatDateForComparison(fecha.toISOString().split('T')[0]));
                }

                // Filtrar datos por turno seleccionado
                const turnoSeleccionado = turnoSelector.value;

                let datosFiltrados = datosProductividad;

                if (turnoSeleccionado !== 'todos') {
                    datosFiltrados = datosProductividad.filter(row => row.turno === turnoSeleccionado);
                }

                // Gráfico de productividad
                const optionsProductividad = {
                    series: [{
                        name: 'Productividad',
                        data: diasSemana.map(dia => {
                            const turnosDelDia = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                return fechaTurnoFormateada === dia;
                            });

                            if (turnosDelDia.length === 0) return null;

                            // Calcular el promedio de productividad de todos los turnos del día
                            const productividadTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.productividad_empaque || 0), 0);
                            return parseFloat((productividadTotal / turnosDelDia.length).toFixed(1));
                        })
                    }],
                    chart: {
                        height: 350,
                        type: 'line',
                        toolbar: {
                            show: false
                        }
                    },
                    stroke: {
                        width: 3,
                        curve: 'smooth'
                    },
                    colors: ['#4e73df'],
                    xaxis: {
                        categories: diasSemana.map(dia => formatDateForDisplay(dia)),
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Productividad (pzs/pers/hr)'
                        },
                        min: 0
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val !== null ? val.toFixed(1) + ' pzs/pers/hr' : 'Sin datos';
                            }
                        }
                    },
                    title: {
                        text: 'Productividad Semanal de Empaque',
                        align: 'center',
                        style: {
                            fontSize: '16px',
                            fontWeight: 'bold'
                        }
                    }
                };

                // Gráfico de dotación
                const optionsDotacion = {
                    series: [{
                        name: 'Dotación Real',
                        data: diasSemana.map(dia => {
                            const turnosDelDia = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                return fechaTurnoFormateada === dia;
                            });

                            if (turnosDelDia.length === 0) return null;

                            // Sumar dotación real de todos los turnos del día
                            const dotacionRealTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.dotacion_real || 0), 0);
                            return dotacionRealTotal;
                        })
                    }],
                    chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        }
                    },
                    colors: ['#1cc88a'],
                    xaxis: {
                        categories: diasSemana.map(dia => formatDateForDisplay(dia)),
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Dotación'
                        },
                        min: 0
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val !== null ? val + ' personas' : 'Sin datos';
                            }
                        }
                    },
                    title: {
                        text: 'Dotación Semanal de Empaque',
                        align: 'center',
                        style: {
                            fontSize: '16px',
                            fontWeight: 'bold'
                        }
                    }
                };

                // Gráfico de horas trabajadas y tiempo muerto
                const optionsHorasTrabajadas = {
                    series: [{
                        name: 'Horas Trabajadas',
                        data: diasSemana.map(dia => {
                            const turnosDelDia = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                return fechaTurnoFormateada === dia;
                            });

                            if (turnosDelDia.length === 0) return null;

                            // Sumar horas trabajadas de todos los turnos del día
                            const horasTrabajadasTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.horas_trabajadas_empaque || 0), 0);
                            return parseFloat(horasTrabajadasTotal.toFixed(1));
                        })
                    }, {
                        name: 'Tiempo Muerto (horas)',
                        data: diasSemana.map(dia => {
                            const turnosDelDia = datosFiltrados.filter(row => {
                                const fechaTurnoFormateada = formatDateForComparison(row.fecha_turno);
                                return fechaTurnoFormateada === dia;
                            });

                            if (turnosDelDia.length === 0) return null;

                            // Sumar tiempo muerto de todos los turnos del día (convertir minutos a horas)
                            const tiempoMuertoTotal = turnosDelDia.reduce((sum, row) => sum + Number(row.tiempo_muerto_empaque || 0), 0);
                            return parseFloat((tiempoMuertoTotal / 60).toFixed(1));
                        })
                    }],
                    chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        }
                    },
                    colors: ['#36b9cc', '#e74a3b'],
                    xaxis: {
                        categories: diasSemana.map(dia => formatDateForDisplay(dia)),
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Horas'
                        },
                        min: 0
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val !== null ? val.toFixed(1) + ' horas' : 'Sin datos';
                            }
                        }
                    },
                    title: {
                        text: 'Horas Trabajadas y Tiempo Muerto Semanal',
                        align: 'center',
                        style: {
                            fontSize: '16px',
                            fontWeight: 'bold'
                        }
                    }
                };

                // Renderizar gráficos
                document.querySelector("#productividadEmpaqueChart").innerHTML = '';
                document.querySelector("#dotacionEmpaqueChart").innerHTML = '';
                document.querySelector("#horasTrabajadasEmpaqueChart").innerHTML = '';

                new ApexCharts(document.querySelector("#productividadEmpaqueChart"), optionsProductividad).render();
                new ApexCharts(document.querySelector("#dotacionEmpaqueChart"), optionsDotacion).render();
                new ApexCharts(document.querySelector("#horasTrabajadasEmpaqueChart"), optionsHorasTrabajadas).render();
            }

            function calcularKPIs(data) {
                // Suma total de dotaciones y otros valores de la semana
                const totales = data.produccion.reduce((acc, row) => {
                    // Sumar dotaciones
                    acc.dotacionReal += Number(row.dotacion_real) || 0;
                    acc.dotacionEsperada += Number(row.dotacion_esperada) || 0;

                    // Resto de cálculos
                    acc.tiempoEfectivo += (Number(row.minutos_efectivos) / (Number(row.minutos_efectivos) + Number(row.tiempo_muerto_minutos))) * 100 || 0;
                    acc.kilos_premium += Number(row.kilos_premium) || 0;
                    acc.kilos_recepcion += Number(row.kilos_recepcion) || 0;
                    acc.kilos_entrega += Number(row.kilos_entrega) || 0;
                    acc.count++;
                    return acc;
                }, {
                    dotacionReal: 0,
                    dotacionEsperada: 0,
                    tiempoEfectivo: 0,
                    kilos_premium: 0,
                    kilos_recepcion: 0,
                    kilos_entrega: 0,
                    count: 0
                });

                // Cálculo de KPIs
                const ausentismo = totales.dotacionEsperada > 0 ?
                    ((totales.dotacionEsperada - totales.dotacionReal) / totales.dotacionEsperada * 100) : 0;

                const dotacion = totales.dotacionEsperada > 0 ?
                    (totales.dotacionReal / totales.dotacionEsperada * 100) : 0;

                const rendimientoGeneral = totales.kilos_entrega > 0 ?
                    (totales.kilos_recepcion / totales.kilos_entrega * 100) : 0;

                const rendimientoPremium = totales.kilos_recepcion > 0 ?
                    (totales.kilos_premium / totales.kilos_recepcion * 100) : 0;

                return {
                    ausentismo: ausentismo.toFixed(1),
                    tiempoEfectivo: (totales.tiempoEfectivo / totales.count).toFixed(1),
                    rendimientoPremium: rendimientoPremium.toFixed(1),
                    rendimientoGeneral: rendimientoGeneral.toFixed(1),
                    dotacion: dotacion.toFixed(1)
                };
            }

            function actualizarKPIs(kpis) {
                document.getElementById('kpiAusentismo').textContent = `${kpis.ausentismo}%`;
                document.getElementById('kpiTiempoEfectivo').textContent = `${kpis.tiempoEfectivo}%`;
                document.getElementById('kpiRendimientoPremium').textContent = `${kpis.rendimientoPremium}%`;
                document.getElementById('kpiRendimientoGeneral').textContent = `${kpis.rendimientoGeneral}%`;
                document.getElementById('kpiDotacion').textContent = `${kpis.dotacion}%`;
            }

            function actualizarGraficoTiemposMuertosSemanales(data, turnoSeleccionado) {
                if (!data || data.length === 0) {
                    console.log('No hay datos de tiempos muertos');
                    document.querySelector("#tiemposMuertosSemanalChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                    return;
                }

                // Depurar los datos recibidos
                console.log('Datos de tiempos muertos recibidos:', data);
                console.log('Turno seleccionado:', turnoSeleccionado);

                // Agrupar tiempos muertos por departamento
                const tiemposPorDepartamento = {};
                data.forEach(item => {
                    const departamento = item.departamento || 'Sin departamento';
                    if (!tiemposPorDepartamento[departamento]) {
                        tiemposPorDepartamento[departamento] = 0;
                    }
                    tiemposPorDepartamento[departamento] += Number(item.total_minutos_muertos) || 0;
                });

                // Convertir a series para el gráfico
                const departamentos = Object.keys(tiemposPorDepartamento);
                const tiempos = departamentos.map(dep => tiemposPorDepartamento[dep]);

                // Si no hay datos, mostrar mensaje
                if (departamentos.length === 0) {
                    console.log('No hay datos de tiempos muertos por departamento');
                    document.querySelector("#tiemposMuertosSemanalChart").innerHTML = '<div class="flex h-full items-center justify-center"><p class="text-gray-500">No hay datos disponibles</p></div>';
                    return;
                }

                // Ordenar por tiempo (de mayor a menor)
                const datosCombinados = departamentos.map((dep, i) => ({
                    departamento: dep,
                    tiempo: tiempos[i]
                }));

                datosCombinados.sort((a, b) => b.tiempo - a.tiempo);

                // Tomar los 5 departamentos con más tiempo muerto
                const top5Departamentos = datosCombinados.slice(0, 5);

                const optionsTiemposMuertosSemanales = {
                    series: [{ data: top5Departamentos.map(d => d.tiempo) }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        }
                    },
                    colors: ['#F87171', '#FB923C', '#FBBF24', '#A3E635', '#34D399'],
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return formatearTiempo(val);
                        },
                        style: {
                            fontSize: '12px',
                            colors: ['#304758']
                        }
                    },
                    xaxis: {
                        categories: top5Departamentos.map(d => d.departamento),
                        labels: {
                            formatter: function (val) {
                                return formatearTiempo(val);
                            }
                        },
                        title: {
                            text: 'Tiempo (hh:mm)'
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                // Limitar longitud del texto
                                if (typeof val === 'string' && val.length > 15) {
                                    return val.substring(0, 15) + '...';
                                }
                                return val;
                            }
                        }
                    },
                    title: {
                        text: `Tiempos Muertos por Departamento${turnoSeleccionado === 'todos' ? '' : ` - Turno ${turnoSeleccionado}`}`,
                        align: 'left',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold'
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return formatearTiempo(val);
                            }
                        }
                    }
                };

                document.querySelector("#tiemposMuertosSemanalChart").innerHTML = '';
                new ApexCharts(document.querySelector("#tiemposMuertosSemanalChart"), optionsTiemposMuertosSemanales).render();
            }

            // Eventos para recargar datos
            fechaInput.addEventListener('change', cargarDatos);
            tipoPlanillaSelect.addEventListener('change', function () {
                const tipoPlanilla = tipoPlanillaSelect.value;
                const unidadMeta = document.getElementById('unidadMeta');
                unidadMeta.textContent = tipoPlanilla === 'Porciones' ? 'kg' : (tipoPlanilla === 'Empaque') ? 'unidades' : 'pzs';

                // Mostrar u ocultar gráficos según el tipo de planilla
                const graficosProduccion = document.getElementById('graficos-produccion');
                const graficosEmpaque = document.getElementById('graficos-empaque');

                if (tipoPlanilla === 'Empaque') {
                    graficosProduccion.classList.add('hidden');
                    graficosEmpaque.classList.remove('hidden');
                } else {
                    graficosProduccion.classList.remove('hidden');
                    graficosEmpaque.classList.add('hidden');
                }

                cargarDatos();
            });
            turnoSelector.addEventListener('change', function () {
                // Actualizar el título con el turno seleccionado
                document.getElementById('turnoTitulo').textContent = this.value === 'todos' ? 'Todos' : this.value;
                cargarDatos();
            });
            metaProductividadInput.addEventListener('change', cargarDatos);

            // Cargar datos iniciales
            cargarDatos();
        });
    </script>
@endsection