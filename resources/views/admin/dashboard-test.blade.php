<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Productividad - Test</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --primary: #1e40af;
            --secondary: #64748b;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }

        .card {
            @apply bg-white rounded-lg shadow-md p-4 border border-gray-200;
        }

        .card-header {
            @apply flex justify-between items-center mb-4;
        }

        .card-title {
            @apply text-lg font-semibold text-gray-800;
        }

        .card-content {
            @apply space-y-4;
        }

        .tab-active {
            @apply bg-blue-500 text-white;
        }

        .tab-inactive {
            @apply bg-gray-100 text-gray-600 hover:bg-gray-200;
        }

        .btn {
            @apply px-4 py-2 rounded-md text-sm font-medium transition-colors;
        }

        .btn-primary {
            @apply bg-blue-500 text-white hover:bg-blue-600;
        }

        .btn-outline {
            @apply border border-gray-300 bg-white text-gray-700 hover:bg-gray-50;
        }

        .select-input {
            @apply w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200">
            <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-bold text-gray-800">Dashboard de Productividad</h1>
                    <div class="flex space-x-4">
                        <button id="datePicker" class="btn btn-outline flex items-center">
                            <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                            <span>{{ date('d/m/Y') }}</span>
                        </button>
                        <select id="turnoSelect" class="select-input">
                            <option value="1">Turno Día</option>
                            <option value="2">Turno Tarde</option>
                            <option value="3">Turno Noche</option>
                        </select>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <!-- KPI Cards -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total Piezas Producidas</h3>
                        <i data-lucide="box" class="w-4 h-4 text-gray-500"></i>
                    </div>
                    <div class="card-content">
                        <div id="total-piezas" class="text-2xl font-bold">
                            {{ number_format($kpis['total_piezas'], 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            <span id="total-piezas-diff" class="flex items-center">
                                <!-- Se actualizará vía JS -->
                            </span>
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Productividad Promedio</h3>
                        <i data-lucide="trending-up" class="w-4 h-4 text-gray-500"></i>
                    </div>
                    <div class="card-content">
                        <div id="productividad-promedio" class="text-2xl font-bold">
                            {{ number_format($kpis['productividad_promedio'], 1, ',', '.') }} pzs/pers/hora
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rendimiento Promedio</h3>
                        <i data-lucide="percent" class="w-4 h-4 text-gray-500"></i>
                    </div>
                    <div class="card-content">
                        <div id="rendimiento-promedio" class="text-2xl font-bold">
                            {{ number_format($kpis['rendimiento_promedio'], 1, ',', '.') }}%
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tiempo Muerto Total</h3>
                        <i data-lucide="clock" class="w-4 h-4 text-gray-500"></i>
                    </div>
                    <div class="card-content">
                        <div id="tiempo-muerto" class="text-2xl font-bold">
                            {{ floor($kpis['tiempo_muerto_total'] / 60) }}h {{ $kpis['tiempo_muerto_total'] % 60 }}m
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs y Gráficos -->
            <div class="card mb-8">
                <div class="card-header">
                    <div class="flex space-x-2">
                        <button class="tab-button tab-active px-4 py-2 rounded-md" data-tab="productividad">
                            Productividad
                        </button>
                        <button class="tab-button tab-inactive px-4 py-2 rounded-md" data-tab="tiempomuerto">
                            Tiempo Muerto
                        </button>
                        <button class="tab-button tab-inactive px-4 py-2 rounded-md" data-tab="rendimiento">
                            Rendimiento
                        </button>
                    </div>
                </div>

                <div class="card-content">
                    <div id="tab-productividad" class="tab-content">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="h-[300px]">
                                <canvas id="productividad-semanal-chart"></canvas>
                            </div>
                            <div class="h-[300px]">
                                <canvas id="productividad-departamento-chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div id="tab-tiempomuerto" class="tab-content hidden">
                        <!-- Similar structure for tiempo muerto charts -->
                    </div>

                    <div id="tab-rendimiento" class="tab-content hidden">
                        <!-- Similar structure for rendimiento charts -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Inicializaciones básicas
        lucide.createIcons();

        flatpickr("#datePicker", {
            locale: "es",
            dateFormat: "Y-m-d",
            defaultDate: "{{ $fecha }}",
            onChange: function (selectedDates) {
                const fecha = selectedDates[0].toISOString().split('T')[0];
                const turno = document.getElementById('turnoSelect').value;
                updateDashboard(fecha, turno);
            }
        });

        // Función para formatear números
        function formatNumber(number, decimals = 0) {
            return new Intl.NumberFormat('es-CL', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }).format(number);
        }

        // Función para actualizar KPIs
        function updateKPIs(data) {
            document.getElementById('total-piezas').textContent = formatNumber(data.kpis.total_piezas);
            document.getElementById('productividad-promedio').textContent =
                `${formatNumber(data.kpis.productividad_promedio, 1)} pzs/pers/hora`;
            document.getElementById('rendimiento-promedio').textContent =
                `${formatNumber(data.kpis.rendimiento_promedio, 1)}%`;

            const tiempoMuerto = data.kpis.tiempo_muerto_total;
            const horas = Math.floor(tiempoMuerto / 60);
            const minutos = tiempoMuerto % 60;
            document.getElementById('tiempo-muerto').textContent = `${horas}h ${minutos}m`;
        }

        // Inicializar gráficos
        let charts = {};

        function initializeCharts() {
            const ctx = document.getElementById('productividad-semanal-chart').getContext('2d');
            charts.productividad = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Productividad',
                        data: [],
                        backgroundColor: 'rgba(59, 130, 246, 0.5)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Inicializar otros gráficos...
        }

        // Función para actualizar gráficos
        function updateCharts(data) {
            charts.productividad.data = data.graficos.productividad;
            charts.productividad.update();
            // Actualizar otros gráficos...
        }

        // Función principal para actualizar el dashboard
        async function updateDashboard(fecha, turno) {
            try {
                const response = await fetch(`{{ route('dashboard.data') }}?fecha=${fecha}&turno=${turno}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Datos recibidos:', data); // Para debug
                updateKPIs(data);
                updateCharts(data);

            } catch (error) {
                console.error('Error actualizando dashboard:', error);
                // Mostrar mensaje de error al usuario
                alert('Error al cargar los datos del dashboard');
            }
        }

        // Inicializar todo cuando el documento esté listo
        document.addEventListener('DOMContentLoaded', function () {
            initializeCharts();

            // Event listener para el selector de turno
            document.getElementById('turnoSelect').addEventListener('change', (e) => {
                const fecha = document.getElementById('datePicker')._flatpickr.selectedDates[0]
                    .toISOString().split('T')[0];
                updateDashboard(fecha, e.target.value);
            });

            // Cargar datos iniciales
            updateDashboard('{{ $fecha }}', {{ $turno }});
        });
    </script>
</body>

</html>