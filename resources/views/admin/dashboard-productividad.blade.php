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

        <!-- Contenedor de Gráficos -->
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                <!-- Gráfico de Productividad -->
                <div class="bg-white p-6 rounded-lg shadow">
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
                                    value="800" step="1">
                                <span class="text-sm text-gray-500">kg/hora/persona</span>
                            </div>
                        </div>
                    </div>
                    <div id="productividadChart" class="h-[500px]"></div>
                </div>

                <!-- Gráfico de Rendimiento -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Rendimiento por Turno</h3>
                        <div class="flex items-center space-x-2">
                            <label for="turnoSelectorRendimiento" class="text-sm font-medium text-gray-700">Turno:</label>
                            <select id="turnoSelectorRendimiento"
                                class="w-32 px-2 py-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="todos">Todos</option>
                                <option value="Día">Día</option>
                                <option value="Tarde">Tarde</option>
                                <option value="Noche">Noche</option>
                            </select>
                        </div>
                    </div>
                    <div id="rendimientoChart" class="h-[500px]"></div>
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
            const turnoSelectorRendimiento = document.getElementById('turnoSelectorRendimiento');

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('es-CL', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            function cargarDatos() {
                const fecha = fechaInput.value;
                const tipoPlanilla = tipoPlanillaSelect.value;

                fetch(`/pst/public/api/dashboard-data?fecha=${fecha}&tipo_planilla=${tipoPlanilla}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos recibidos:', data);
                        actualizarGraficos(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function actualizarGraficos(data) {
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

                const turnoSeleccionado = turnoSelector.value;
                const turnoSeleccionadoRendimiento = turnoSelectorRendimiento.value;
                const turnos = ['Día', 'Tarde', 'Noche'];

                // Gráfico de Productividad
                let seriesProductividad;
                if (turnoSeleccionado === 'todos') {
                    seriesProductividad = turnos.map(turno => ({
                        name: `Productividad ${turno}`,
                        type: 'bar',
                        data: diasSemana.map(dia => {
                            const turnoData = data.find(row =>
                                formatDate(row.fecha_turno) === dia.fechaFormateada &&
                                row.turno_nombre === turno
                            );
                            return turnoData ? Number(turnoData.productividad_kg_hora_persona) : null;
                        })
                    }));
                } else {
                    seriesProductividad = [{
                        name: 'Productividad',
                        type: 'bar',
                        data: diasSemana.map(dia => {
                            const turnoData = data.find(row =>
                                formatDate(row.fecha_turno) === dia.fechaFormateada &&
                                row.turno_nombre === turnoSeleccionado
                            );
                            return turnoData ? Number(turnoData.productividad_kg_hora_persona) : null;
                        })
                    }];
                }

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
                            return val.toFixed(2);
                        }
                    },
                    colors: ['#008FFB', '#00E396', '#FEB019'],
                    xaxis: {
                        categories: diasSemana.map(dia => `${dia.nombreDia} ${dia.fechaFormateada}`),
                        title: {
                            text: 'Fecha'
                        }
                    },
                    yaxis: {
                        title: {
                            text: "Productividad (kg/hora/persona)"
                        },
                        min: 0
                    },
                    annotations: {
                        yaxis: [{
                            y: parseFloat(metaProductividadInput.value),
                            borderColor: '#FF0000',
                            label: {
                                borderColor: '#FF0000',
                                style: {
                                    color: '#fff',
                                    background: '#FF0000'
                                },
                                text: 'Meta de Productividad'
                            }
                        }]
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                if (val === null) return 'Sin datos';
                                return val.toFixed(2) + ' kg/hora/persona';
                            }
                        }
                    }
                };

                // Calcular rendimientos totales por día
                const rendimientosPorDia = diasSemana.map(dia => {
                    // Obtener todos los turnos de ese día
                    const turnosDelDia = data.filter(row => formatDate(row.fecha_turno) === dia.fechaFormateada);

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
                        name: 'Rendimiento Total',
                        type: 'line',
                        data: rendimientosPorDia.map(d => d.rendimiento)
                    },
                    {
                        name: 'Rendimiento Premium Total',
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
                    markers: {
                        size: 6,
                        shape: "circle",
                        strokeWidth: 2,
                        hover: {
                            size: 8
                        }
                    },
                    xaxis: {
                        categories: diasSemana.map(dia => `${dia.nombreDia} ${dia.fechaFormateada}`),
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
                        tickAmount: 10
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

                // Renderizar gráficos
                document.querySelector("#productividadChart").innerHTML = '';
                document.querySelector("#rendimientoChart").innerHTML = '';
                new ApexCharts(document.querySelector("#productividadChart"), optionsProductividad).render();
                new ApexCharts(document.querySelector("#rendimientoChart"), optionsRendimiento).render();
            }

            // Eventos para recargar datos
            fechaInput.addEventListener('change', cargarDatos);
            tipoPlanillaSelect.addEventListener('change', cargarDatos);
            metaProductividadInput.addEventListener('change', cargarDatos);
            turnoSelector.addEventListener('change', cargarDatos);
            turnoSelectorRendimiento.addEventListener('change', cargarDatos);

            // Cargar datos iniciales
            cargarDatos();
        });
    </script>
@endsection