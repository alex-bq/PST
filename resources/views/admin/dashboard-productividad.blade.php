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
                    </select>
                </div>
            </div>
        </div>

        <!-- Panel de KPIs -->
        <div class="p-6">
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

            <!-- Gráficos principales -->
            <div class="grid grid-cols-6 gap-6">
                <!-- Gráfico de Productividad (ocupa 4/6) -->
                <div class="col-span-4 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Productividad por Turno</h3>
                        <div class="flex items-center space-x-4">
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
                        <h3 class="text-lg font-semibold">Rendimiento Semanal</h3>
                    </div>
                    <div id="rendimientoChart" class="h-[400px]"></div>
                </div>

                <!-- Gráfico de Dotación -->
                <div class="col-span-2 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Dotación Semanal</h3>
                    </div>
                    <div id="dotacionChart" class="h-[300px]"></div>
                </div>

                <!-- Gráfico de Tiempos Muertos -->
                <div class="col-span-2 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Distribución de Tiempo Diario</h3>
                    </div>
                    <div id="tiemposMuertosChart" class="h-[300px]"></div>
                </div>

                <!-- Gráfico de Tiempos Muertos por Departamento -->
                <div class="col-span-2 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Tiempos Muertos por Departamento</h3>
                    </div>
                    <div id="tiemposMuertosSemanalChart" class="h-[300px]"></div>
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
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
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
                const tipoPlanilla = tipoPlanillaSelect.value;

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
                        "#tiemposMuertosSemanalChart"
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
                        console.log('Datos originales:', data);

                        // Verificar si hay datos
                        if (!data.produccion || data.produccion.length === 0) {
                            console.log('No hay datos de producción');
                            mostrarMensajeNoDatos();
                            return;
                        }

                        console.log('Enviando datos a actualizarGraficos:', data.produccion);
                        // Si hay datos, actualizar gráficos
                        actualizarGraficos(data.produccion);

                        // Actualizar gráfico de tiempos muertos solo si hay datos
                        if (data.tiempos_muertos && data.tiempos_muertos.length > 0) {
                            actualizarGraficoTiemposMuertosSemanales(data.tiempos_muertos);
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
                console.log('Inicio actualizarGraficos con datos:', data);

                // Obtener la fecha seleccionada y calcular el inicio/fin de la semana
                const fechaSeleccionada = new Date(fechaInput.value);
                const inicioSemana = new Date(fechaSeleccionada);
                inicioSemana.setDate(fechaSeleccionada.getDate() - fechaSeleccionada.getDay() + 1); // Lunes
                const finSemana = new Date(inicioSemana);
                finSemana.setDate(inicioSemana.getDate() + 6); // Domingo

                // Generar array con todos los días de la semana
                const diasSemana = [];
                const nombresDias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                for (let d = new Date(inicioSemana); d <= finSemana; d.setDate(d.getDate() + 1)) {
                    diasSemana.push({
                        fecha: new Date(d),
                        nombreDia: nombresDias[d.getDay() === 0 ? 6 : d.getDay() - 1],
                        fechaFormateada: formatDate(d)
                    });
                }

                console.log('Días de la semana generados:', diasSemana);

                const turnoSeleccionado = turnoSelector.value;
                const turnos = ['Día', 'Tarde', 'Noche'];

                // Gráfico de Productividad
                let seriesProductividad;
                if (turnoSeleccionado === 'todos') {
                    seriesProductividad = turnos.map(turno => {
                        const serieData = {
                            name: turno,
                            type: 'bar',
                            data: diasSemana.map(dia => {
                                const turnoData = data.find(row => {
                                    console.log('Comparando fechas:', {
                                        rowFecha: row.fecha_turno,
                                        diaFecha: formatDate(dia.fecha),
                                        rowTurno: row.turno_nombre,
                                        turno: turno
                                    });
                                    return row.fecha_turno === formatDate(dia.fecha) &&
                                        row.turno_nombre === turno;
                                });

                                if (turnoData) {
                                    return tipoPlanillaSelect.value === 'Porciones' ?
                                        Number(turnoData.kilos_recepcion) :
                                        Number(turnoData.piezas_recepcion);
                                }
                                return null;
                            })
                        };
                        return serieData;
                    });
                } else {
                    seriesProductividad = [{
                        name: 'Productividad',
                        type: 'bar',
                        data: diasSemana.map(dia => {
                            const turnoData = data.find(row =>
                                formatDate(row.fecha_turno) === dia.fechaFormateada &&
                                row.turno_nombre === turnoSeleccionado
                            );
                            // Si es Porciones, usar kilos_recepcion, si no usar piezas_recepcion
                            if (turnoData) {
                                if (tipoPlanillaSelect.value === 'Porciones') {
                                    return Number(turnoData.kilos_recepcion);
                                } else {
                                    return Number(turnoData.piezas_recepcion);
                                }
                            }
                            return null;
                        })
                    }];
                }

                // Determinar la unidad de medida según el tipo de planilla
                const tipoPlanilla = tipoPlanillaSelect.value;
                const unidadMedida = (tipoPlanilla === 'Porciones') ? 'Kilos' : 'Piezas';

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
                            const fecha = new Date(dia.fecha);
                            const nombreDia = fecha.toLocaleDateString('es-ES', { weekday: 'short' });
                            const numeroDia = fecha.getDate();
                            return `${numeroDia} ${nombreDia}`;
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
                                text: `Meta: ${metaProductividadInput.value} ${tipoPlanillaSelect.value === 'Porciones' ? 'kg' : 'pzs'}`
                            }
                        }]
                    }
                };

                // Gráfico de Rendimiento
                let seriesRendimiento = [{
                    name: 'General',
                    type: 'line',
                    data: diasSemana.map(dia => {
                        const datosDia = data.filter(row =>
                            row.fecha_turno === dia.fechaFormateada &&
                            (turnoSeleccionado === 'todos' || row.turno_nombre === turnoSeleccionado)
                        );

                        console.log(`Datos para ${dia.fechaFormateada} (General):`, datosDia);

                        if (datosDia.length > 0) {
                            const rendimientoPromedio = datosDia.reduce((sum, row) =>
                                sum + Number(row.rendimiento), 0) / datosDia.length;
                            return rendimientoPromedio;
                        }
                        return null;
                    })
                }, {
                    name: 'Premium',
                    type: 'line',
                    data: diasSemana.map(dia => {
                        const datosDia = data.filter(row =>
                            row.fecha_turno === dia.fechaFormateada &&
                            (turnoSeleccionado === 'todos' || row.turno_nombre === turnoSeleccionado)
                        );

                        console.log(`Datos para ${dia.fechaFormateada} (Premium):`, datosDia);

                        if (datosDia.length > 0) {
                            const rendimientoPremiumPromedio = datosDia.reduce((sum, row) =>
                                sum + Number(row.rendimiento_premium), 0) / datosDia.length;
                            return rendimientoPremiumPromedio;
                        }
                        return null;
                    })
                }];

                // Agregar logs para verificar
                console.log('Series Rendimiento procesadas:', seriesRendimiento);

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
                            return val !== null ? val.toFixed(2) + '%' : '0%';
                        },
                        offsetY: -10
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    },
                    colors: ['#FF4560', '#775DD0'],
                    markers: {
                        size: 6,
                        shape: "circle",
                        strokeWidth: 2,
                        hover: {
                            size: 8
                        }
                    },
                    xaxis: {
                        categories: diasSemana.map(dia => {
                            const fecha = new Date(dia.fecha);
                            const nombreDia = fecha.toLocaleDateString('es-ES', { weekday: 'short' });
                            const numeroDia = fecha.getDate();
                            return `${numeroDia} ${nombreDia}`;
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
                                return val !== null ? val.toFixed(2) : '0';
                            }
                        }
                    },
                    title: {
                        text: '',
                        align: 'left',
                        style: {
                            fontSize: '16px',
                            fontWeight: 'bold'
                        }
                    },
                    tooltip: {
                        shared: true,
                        y: {
                            formatter: function (val) {
                                return val !== null ? val.toFixed(2) + '%' : '0%';
                            }
                        }
                    }
                };

                // Gráfico de Dotación
                let seriesDotacion = [{
                    name: 'Dotación Real',
                    type: 'line',
                    data: diasSemana.map(dia => {
                        const datosDia = data.filter(row =>
                            row.fecha_turno === dia.fechaFormateada &&
                            (turnoSeleccionado === 'todos' || row.turno_nombre === turnoSeleccionado)
                        );

                        console.log(`Datos para ${dia.fechaFormateada} (Dotación Real):`, datosDia);

                        if (datosDia.length > 0) {
                            const dotacionRealPromedio = datosDia.reduce((sum, row) =>
                                sum + Number(row.dotacion_real), 0) / datosDia.length;
                            return dotacionRealPromedio;
                        }
                        return null;
                    })
                }, {
                    name: 'Dotación Esperada',
                    type: 'line',
                    data: diasSemana.map(dia => {
                        const datosDia = data.filter(row =>
                            row.fecha_turno === dia.fechaFormateada &&
                            (turnoSeleccionado === 'todos' || row.turno_nombre === turnoSeleccionado)
                        );

                        console.log(`Datos para ${dia.fechaFormateada} (Dotación Esperada):`, datosDia);

                        if (datosDia.length > 0) {
                            const dotacionEsperadaPromedio = datosDia.reduce((sum, row) =>
                                sum + Number(row.dotacion_esperada), 0) / datosDia.length;
                            return dotacionEsperadaPromedio;
                        }
                        return null;
                    })
                }];

                // Agregar logs para verificar
                console.log('Series Dotación procesadas:', seriesDotacion);

                const optionsDotacion = {
                    series: seriesDotacion,
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
                            const fecha = new Date(dia.fecha);
                            const nombreDia = fecha.toLocaleDateString('es-ES', { weekday: 'short' });
                            const numeroDia = fecha.getDate();
                            return `${numeroDia} ${nombreDia}`;
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
                let seriesTiemposDiarios = [{
                    name: 'Horas Trabajadas',
                    data: diasSemana.map(dia => {
                        const datosDia = data.filter(row =>
                            row.fecha_turno === dia.fechaFormateada &&
                            (turnoSeleccionado === 'todos' || row.turno_nombre === turnoSeleccionado)
                        );

                        console.log(`Datos para ${dia.fechaFormateada} (Horas Trabajadas):`, datosDia);

                        if (datosDia.length > 0) {
                            const horasTrabajadasPromedio = datosDia.reduce((sum, row) =>
                                sum + Number(row.minutos_efectivos), 0) / datosDia.length;
                            return horasTrabajadasPromedio;
                        }
                        return null;
                    })
                }, {
                    name: 'Tiempo Muerto',
                    data: diasSemana.map(dia => {
                        const datosDia = data.filter(row =>
                            row.fecha_turno === dia.fechaFormateada &&
                            (turnoSeleccionado === 'todos' || row.turno_nombre === turnoSeleccionado)
                        );

                        console.log(`Datos para ${dia.fechaFormateada} (Tiempo Muerto):`, datosDia);

                        if (datosDia.length > 0) {
                            const tiempoMuertoPromedio = datosDia.reduce((sum, row) =>
                                sum + Number(row.tiempo_muerto_minutos), 0) / datosDia.length;
                            return tiempoMuertoPromedio;
                        }
                        return null;
                    })
                }];

                // Agregar logs para verificar
                console.log('Series Tiempos procesadas:', seriesTiemposDiarios);

                const optionsTiempoDiario = {
                    series: seriesTiemposDiarios,
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
                            columnWidth: '70%'
                        }
                    },
                    colors: ['#10B981', '#EF4444'],
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return formatearTiempo(val);
                        }
                    },
                    xaxis: {
                        categories: diasSemana.map(dia => {
                            const fecha = new Date(dia.fecha);
                            const nombreDia = fecha.toLocaleDateString('es-ES', { weekday: 'short' });
                            const numeroDia = fecha.getDate();
                            return `${numeroDia} ${nombreDia}`;
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

                // Después de procesar cada serie
                console.log('Series Rendimiento:', seriesRendimiento);
                console.log('Series Dotación:', seriesDotacion);
                console.log('Series Tiempos:', seriesTiemposDiarios);

                // Antes de renderizar cada gráfico
                console.log('Datos gráfico rendimiento:', {
                    series: optionsRendimiento.series,
                    categorias: optionsRendimiento.xaxis.categories
                });

                console.log('Datos gráfico dotación:', {
                    series: optionsDotacion.series,
                    categorias: optionsDotacion.xaxis.categories
                });

                console.log('Datos gráfico tiempos:', {
                    series: optionsTiempoDiario.series,
                    categorias: optionsTiempoDiario.xaxis.categories
                });

                // Renderizar gráficos
                document.querySelector("#productividadChart").innerHTML = '';
                document.querySelector("#rendimientoChart").innerHTML = '';
                document.querySelector("#dotacionChart").innerHTML = '';
                document.querySelector("#tiemposMuertosChart").innerHTML = '';

                new ApexCharts(document.querySelector("#productividadChart"), optionsProductividad).render();
                new ApexCharts(document.querySelector("#rendimientoChart"), optionsRendimiento).render();
                new ApexCharts(document.querySelector("#dotacionChart"), optionsDotacion).render();
                new ApexCharts(document.querySelector("#tiemposMuertosChart"), optionsTiempoDiario).render();

                // Calcular KPIs
                const kpis = calcularKPIs(data);
                actualizarKPIs(kpis);
            }

            function calcularKPIs(data) {
                // Suma total de dotaciones y otros valores de la semana
                const totales = data.reduce((acc, row) => {
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

            function actualizarGraficoTiemposMuertosSemanales(data) {
                if (!data || data.length === 0) {
                    console.log('No hay datos de tiempos muertos');
                    return;
                }

                const container = document.querySelector("#tiemposMuertosSemanalChart");
                if (!container) return;

                // Limpiar el contenedor
                container.innerHTML = '';

                // Agrupar datos por departamento
                const departamentosData = data.reduce((acc, item) => {
                    const depto = item.departamento || 'Sin departamento';
                    if (!acc[depto]) {
                        acc[depto] = {
                            tiempoMuerto: 0,
                            horasTrabajadas: 0
                        };
                    }
                    acc[depto].tiempoMuerto += parseInt(item.total_minutos_muertos) || 0;
                    acc[depto].horasTrabajadas += parseFloat(item.horas_trabajadas || 0) * 60;
                    return acc;
                }, {});

                const seriesData = Object.entries(departamentosData)
                    .map(([depto, datos]) => ({
                        departamento: depto,
                        tiempoMuerto: datos.tiempoMuerto,
                        horasTrabajadas: datos.horasTrabajadas
                    }))
                    .sort((a, b) => b.tiempoMuerto - a.tiempoMuerto);

                const options = {
                    series: [{
                        name: 'Tiempo Muerto',
                        data: seriesData.map(item => item.tiempoMuerto)
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: '70%',
                            distributed: true,
                            dataLabels: {
                                position: 'center'
                            }
                        }
                    },
                    colors: [
                        '#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0',
                        '#546E7A', '#26a69a', '#D10CE8', '#FF6B6B', '#4CAF50',
                        '#2196F3', '#FF9800', '#795548', '#607D8B'
                    ],
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return formatearTiempo(val);
                        },
                        style: {
                            fontSize: '12px',
                            colors: ['#fff'],
                            fontWeight: 'bold'
                        }
                    },
                    legend: {
                        show: false
                    },
                    xaxis: {
                        categories: seriesData.map(item => item.departamento),
                        labels: {
                            formatter: function (val) {
                                return formatearTiempo(val);
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Departamentos'
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

                try {
                    new ApexCharts(container, options).render();
                } catch (error) {
                    console.error('Error al renderizar el gráfico:', error);
                    container.innerHTML = 'Error al cargar el gráfico';
                }
            }

            // Eventos para recargar datos
            fechaInput.addEventListener('change', cargarDatos);
            tipoPlanillaSelect.addEventListener('change', cargarDatos);
            metaProductividadInput.addEventListener('change', cargarDatos);
            turnoSelector.addEventListener('change', cargarDatos);

            // Actualizar la unidad de la meta cuando cambie el tipo de planilla
            tipoPlanillaSelect.addEventListener('change', function () {
                const unidadMeta = document.getElementById('unidadMeta');
                unidadMeta.textContent = this.value === 'Porciones' ? 'kg' : 'pzs';
                cargarDatos();
            });

            // Cargar datos iniciales
            cargarDatos();
        });
    </script>
@endsection