<!-- resources/views/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Productividad</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ["class"],
            theme: {
                container: {
                    center: true,
                    padding: "2rem",
                    screens: {
                        "2xl": "1400px",
                    },
                },
                extend: {
                    colors: {
                        border: "hsl(var(--border))",
                        input: "hsl(var(--input))",
                        ring: "hsl(var(--ring))",
                        background: "hsl(var(--background))",
                        foreground: "hsl(var(--foreground))",
                        primary: {
                            DEFAULT: "hsl(var(--primary))",
                            foreground: "hsl(var(--primary-foreground))",
                        },
                        secondary: {
                            DEFAULT: "hsl(var(--secondary))",
                            foreground: "hsl(var(--secondary-foreground))",
                        },
                        destructive: {
                            DEFAULT: "hsl(var(--destructive))",
                            foreground: "hsl(var(--destructive-foreground))",
                        },
                        muted: {
                            DEFAULT: "hsl(var(--muted))",
                            foreground: "hsl(var(--muted-foreground))",
                        },
                        accent: {
                            DEFAULT: "hsl(var(--accent))",
                            foreground: "hsl(var(--accent-foreground))",
                        },
                        popover: {
                            DEFAULT: "hsl(var(--popover))",
                            foreground: "hsl(var(--popover-foreground))",
                        },
                        card: {
                            DEFAULT: "hsl(var(--card))",
                            foreground: "hsl(var(--card-foreground))",
                        },
                    },
                    borderRadius: {
                        lg: "var(--radius)",
                        md: "calc(var(--radius) - 2px)",
                        sm: "calc(var(--radius) - 4px)",
                    },
                },
            },
        }
    </script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Flatpickr para el selector de fecha -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- CSS personalizado -->
    <style>
        :root {
            --border: 220 13% 91%;
            --input: 220 13% 91%;
            --ring: 224 71.4% 45.1%;
            --background: 0 0% 100%;
            --foreground: 224 71.4% 4.1%;
            --primary: 220.9 39.3% 11%;
            --primary-foreground: 210 20% 98%;
            --secondary: 220 14.3% 95.9%;
            --secondary-foreground: 220.9 39.3% 11%;
            --destructive: 0 84.2% 60.2%;
            --destructive-foreground: 210 20% 98%;
            --muted: 220 14.3% 95.9%;
            --muted-foreground: 220 8.9% 46.1%;
            --accent: 220 14.3% 95.9%;
            --accent-foreground: 220.9 39.3% 11%;
            --popover: 0 0% 100%;
            --popover-foreground: 224 71.4% 4.1%;
            --card: 0 0% 100%;
            --card-foreground: 224 71.4% 4.1%;
            --radius: 0.5rem;

            --chart-1: 222.2 47.4% 11.2%;
            --chart-2: 215 50% 23%;
            --chart-3: 142 71% 45%;
            --chart-4: 346 77% 49.8%;
            --chart-5: 24 75% 50%;
        }

        .card {
            @apply rounded-lg border bg-card text-card-foreground shadow-sm;
        }

        .card-header {
            @apply flex flex-col space-y-1.5 p-6;
        }

        .card-title {
            @apply text-lg font-semibold leading-none tracking-tight;
        }

        .card-description {
            @apply text-sm text-muted-foreground;
        }

        .card-content {
            @apply p-6 pt-0;
        }

        .tab-list {
            @apply inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground;
        }

        .tab-trigger {
            @apply inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm;
        }

        .button {
            @apply inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50;
        }

        .button-outline {
            @apply border border-input bg-background hover:bg-accent hover:text-accent-foreground;
        }

        .select-trigger {
            @apply flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50;
        }

        .popover-content {
            @apply z-50 w-72 rounded-md border bg-popover p-4 text-popover-foreground shadow-md outline-none data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2;
        }

        .tab-content {
            @apply mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2;
        }

        .tab-content[hidden] {
            @apply hidden;
        }
    </style>
</head>

<body class="bg-background text-foreground">
    <div class="flex min-h-screen w-full flex-col">
        <div class="border-b">
            <div class="flex h-16 items-center px-4">
                <h2 class="text-lg font-semibold">Dashboard de Productividad</h2>
                <div class="ml-auto flex items-center space-x-4">
                    <button id="date-picker"
                        class="button button-outline w-[240px] justify-start text-left font-normal">
                        <i data-lucide="calendar" class="mr-2 h-4 w-4"></i>
                        <span id="selected-date">{{ date('d/m/Y') }}</span>
                    </button>
                    <div class="relative">
                        <select id="turno-select" class="select-trigger w-[180px]">
                            <option value="dia">Turno Día</option>
                            <option value="tarde">Turno Tarde</option>
                            <option value="noche">Turno Noche</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- KPI Card 1 -->
                <div class="card">
                    <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="card-title text-sm font-medium">
                            Total Piezas Producidas
                        </h3>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            class="h-4 w-4 text-muted-foreground">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </div>
                    <div class="card-content">
                        <div class="text-2xl font-bold">24,417</div>
                        <p class="text-xs text-muted-foreground">
                            <span class="text-red-500 flex items-center">
                                <i data-lucide="arrow-down" class="mr-1 h-4 w-4"></i>
                                2.3% por debajo de la meta
                            </span>
                        </p>
                    </div>
                </div>
                <!-- KPI Card 2 -->
                <div class="card">
                    <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="card-title text-sm font-medium">
                            Productividad Promedio
                        </h3>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            class="h-4 w-4 text-muted-foreground">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <div class="card-content">
                        <div class="text-2xl font-bold">771.3 pzs/pers/hora</div>
                        <p class="text-xs text-muted-foreground">
                            <span class="text-green-500 flex items-center">
                                <i data-lucide="arrow-up" class="mr-1 h-4 w-4"></i>
                                10.2% vs semana anterior
                            </span>
                        </p>
                    </div>
                </div>
                <!-- KPI Card 3 -->
                <div class="card">
                    <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="card-title text-sm font-medium">
                            Rendimiento Promedio
                        </h3>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            class="h-4 w-4 text-muted-foreground">
                            <rect width="20" height="14" x="2" y="5" rx="2" />
                            <path d="M2 10h20" />
                        </svg>
                    </div>
                    <div class="card-content">
                        <div class="text-2xl font-bold">62.2%</div>
                        <p class="text-xs text-muted-foreground">
                            <span class="text-green-500 flex items-center">
                                <i data-lucide="arrow-up" class="mr-1 h-4 w-4"></i>
                                5.1% vs semana anterior
                            </span>
                        </p>
                    </div>
                </div>
                <!-- KPI Card 4 -->
                <div class="card">
                    <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="card-title text-sm font-medium">
                            Tiempo Muerto Total
                        </h3>
                        <i data-lucide="clock" class="h-4 w-4 text-muted-foreground"></i>
                    </div>
                    <div class="card-content">
                        <div class="text-2xl font-bold">1h 0m</div>
                        <p class="text-xs text-muted-foreground">
                            <span class="text-green-500 flex items-center">
                                <i data-lucide="arrow-down" class="mr-1 h-4 w-4"></i>
                                3.2% del tiempo total
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="space-y-4">
                <div class="tab-list" role="tablist" aria-orientation="horizontal">
                    <button class="tab-trigger" role="tab" data-state="active"
                        data-tab="productividad">Productividad</button>
                    <button class="tab-trigger" role="tab" data-state="inactive" data-tab="tiempomuerto">Tiempo
                        Muerto</button>
                    <button class="tab-trigger" role="tab" data-state="inactive"
                        data-tab="rendimiento">Rendimiento</button>
                </div>

                <!-- Tab Content: Productividad -->
                <div class="tab-content" data-tab-content="productividad">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                        <div class="card col-span-4">
                            <div class="card-header">
                                <h3 class="card-title">Productividad Semanal</h3>
                                <p class="card-description">
                                    Productividad (barras) vs Calidad y Meta (líneas)
                                </p>
                            </div>
                            <div class="card-content pl-2">
                                <div class="h-[300px]">
                                    <canvas id="productividad-semanal-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card col-span-3">
                            <div class="card-header">
                                <h3 class="card-title">Productividad por Departamento</h3>
                                <p class="card-description">
                                    Piezas por persona por hora
                                </p>
                            </div>
                            <div class="card-content">
                                <div class="h-[300px]">
                                    <canvas id="productividad-departamento-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Tiempo Muerto -->
                <div class="tab-content hidden" data-tab-content="tiempomuerto">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                        <div class="card col-span-4">
                            <div class="card-header">
                                <h3 class="card-title">Tiempo Muerto por Departamento</h3>
                                <p class="card-description">
                                    Porcentaje del tiempo total
                                </p>
                            </div>
                            <div class="card-content">
                                <div class="h-[300px]">
                                    <canvas id="tiempo-muerto-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card col-span-3">
                            <div class="card-header">
                                <h3 class="card-title">Distribución de Tiempo Muerto</h3>
                                <p class="card-description">
                                    Por departamento
                                </p>
                            </div>
                            <div class="card-content">
                                <div class="h-[300px]">
                                    <canvas id="tiempo-muerto-pie-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Rendimiento -->
                <div class="tab-content hidden" data-tab-content="rendimiento">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                        <div class="card col-span-4">
                            <div class="card-header">
                                <h3 class="card-title">Rendimiento Semanal</h3>
                                <p class="card-description">
                                    Porcentaje de rendimiento por día
                                </p>
                            </div>
                            <div class="card-content pl-2">
                                <div class="h-[300px]">
                                    <canvas id="rendimiento-semanal-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card col-span-3">
                            <div class="card-header">
                                <h3 class="card-title">Rendimiento por Departamento</h3>
                                <p class="card-description">
                                    Porcentaje de rendimiento
                                </p>
                            </div>
                            <div class="card-content">
                                <div class="h-[300px]">
                                    <canvas id="rendimiento-departamento-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen por Departamento -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <div class="card col-span-7">
                    <div class="card-header">
                        <h3 class="card-title">Resumen por Departamento</h3>
                        <p class="card-description">
                            Indicadores clave de rendimiento por departamento
                        </p>
                    </div>
                    <div class="card-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Departamento 1 -->
                            <div class="card bg-muted/50">
                                <div class="card-header pb-2">
                                    <h4 class="card-title text-md">SALA 1</h4>
                                </div>
                                <div class="card-content space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Rendimiento:</span>
                                        <span class="font-medium">47.8%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Productividad:</span>
                                        <span class="font-medium">1324.6 pzs/pers/hora</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Tiempo Muerto:</span>
                                        <span class="font-medium">0h 0m</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Dotación:</span>
                                        <span class="font-medium">1/1</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Departamento 2 -->
                            <div class="card bg-muted/50">
                                <div class="card-header pb-2">
                                    <h4 class="card-title text-md">SALA 3</h4>
                                </div>
                                <div class="card-content space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Rendimiento:</span>
                                        <span class="font-medium">52.2%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Productividad:</span>
                                        <span class="font-medium">1515.3 pzs/pers/hora</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Tiempo Muerto:</span>
                                        <span class="font-medium">0h 0m</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Dotación:</span>
                                        <span class="font-medium">1/1</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Departamento 3 -->
                            <div class="card bg-muted/50">
                                <div class="card-header pb-2">
                                    <h4 class="card-title text-md">SALA AHUMADO</h4>
                                </div>
                                <div class="card-content space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Rendimiento:</span>
                                        <span class="font-medium">41.6%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Productividad:</span>
                                        <span class="font-medium">104.5 kg/pers/hora</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Tiempo Muerto:</span>
                                        <span class="font-medium">1h 0m</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Dotación:</span>
                                        <span class="font-medium">1/1</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Departamento 4 -->
                            <div class="card bg-muted/50">
                                <div class="card-header pb-2">
                                    <h4 class="card-title text-md">SALA 3 HG</h4>
                                </div>
                                <div class="card-content space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Rendimiento:</span>
                                        <span class="font-medium">85.2%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Productividad:</span>
                                        <span class="font-medium">590.0 pzs/pers/hora</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Tiempo Muerto:</span>
                                        <span class="font-medium">0h 0m</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Dotación:</span>
                                        <span class="font-medium">1/1</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Departamento 5 -->
                            <div class="card bg-muted/50">
                                <div class="card-header pb-2">
                                    <h4 class="card-title text-md">SALA 7</h4>
                                </div>
                                <div class="card-content space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Rendimiento:</span>
                                        <span class="font-medium">84.4%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Productividad:</span>
                                        <span class="font-medium">322.3 pzs/pers/hora</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Tiempo Muerto:</span>
                                        <span class="font-medium">0h 0m</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Dotación:</span>
                                        <span class="font-medium">1/1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inicializar los iconos de Lucide
        lucide.createIcons();

        // Inicializar el selector de fecha
        const datePicker = flatpickr("#date-picker", {
            dateFormat: "d/m/Y",
            defaultDate: "today",
            onChange: function (selectedDates, dateStr) {
                document.getElementById('selected-date').textContent = dateStr;
            }
        });

        // Manejo de pestañas
        const tabTriggers = document.querySelectorAll('.tab-trigger');
        const tabContents = document.querySelectorAll('[data-tab-content]');

        tabTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                // Desactivar todas las pestañas
                tabTriggers.forEach(t => {
                    t.setAttribute('data-state', 'inactive');
                });

                // Ocultar todos los contenidos
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Activar la pestaña seleccionada
                trigger.setAttribute('data-state', 'active');

                // Mostrar el contenido correspondiente
                const tabId = trigger.getAttribute('data-tab');
                const activeContent = document.querySelector(`[data-tab-content="${tabId}"]`);
                activeContent.classList.remove('hidden');
            });
        });

        // Datos para los gráficos
        const weeklyProductivityData = [
            { day: "Lunes", productividad: 1324.6, calidad: 92, meta: 1400, rendimiento: 47.8 },
            { day: "Martes", productividad: 1515.3, calidad: 95, meta: 1400, rendimiento: 52.2 },
            { day: "Miércoles", productividad: 1420.1, calidad: 91, meta: 1400, rendimiento: 50.5 },
            { day: "Jueves", productividad: 1380.5, calidad: 94, meta: 1400, rendimiento: 49.3 },
            { day: "Viernes", productividad: 1450.2, calidad: 93, meta: 1400, rendimiento: 51.8 },
        ];

        const departmentData = [
            { name: "SALA 1", rendimiento: 47.8, productividad: 1324.6, tiempoMuerto: 0 },
            { name: "SALA 3", rendimiento: 52.2, productividad: 1515.3, tiempoMuerto: 0 },
            { name: "SALA AHUMADO", rendimiento: 41.6, productividad: 104.5, tiempoMuerto: 1 },
            { name: "SALA 3 HG", rendimiento: 85.2, productividad: 590.0, tiempoMuerto: 0 },
            { name: "SALA 7", rendimiento: 84.4, productividad: 322.3, tiempoMuerto: 0 },
        ];

        const tiempoMuertoData = [
            { name: "SALA 1", value: 0, porcentaje: 0 },
            { name: "SALA 3", value: 0, porcentaje: 0 },
            { name: "SALA AHUMADO", value: 1, porcentaje: 14.3 },
            { name: "SALA 3 HG", value: 0, porcentaje: 0 },
            { name: "SALA 7", value: 0, porcentaje: 0 },
        ];

        // Colores para los gráficos
        const COLORS = [
            'hsl(222.2, 47.4%, 11.2%)',
            'hsl(215, 50%, 23%)',
            'hsl(142, 71%, 45%)',
            'hsl(346, 77%, 49.8%)',
            'hsl(24, 75%, 50%)'
        ];

        // Inicializar gráficos con Chart.js

        // 1. Gráfico de Productividad Semanal
        const productividadSemanalCtx = document.getElementById('productividad-semanal-chart').getContext('2d');
        new Chart(productividadSemanalCtx, {
            type: 'bar',
            data: {
                labels: weeklyProductivityData.map(item => item.day),
                datasets: [
                    {
                        type: 'bar',
                        label: 'Productividad',
                        data: weeklyProductivityData.map(item => item.productividad),
                        backgroundColor: COLORS[0],
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Calidad %',
                        data: weeklyProductivityData.map(item => item.calidad),
                        borderColor: COLORS[2],
                        backgroundColor: COLORS[2],
                        yAxisID: 'y1',
                        order: 0
                    },
                    {
                        type: 'line',
                        label: 'Meta',
                        data: weeklyProductivityData.map(item => item.meta),
                        borderColor: COLORS[1],
                        backgroundColor: COLORS[1],
                        borderDash: [5, 5],
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'pzs/pers/hora'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: '%'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });

        // 2. Gráfico de Productividad por Departamento
        const productividadDepartamentoCtx = document.getElementById('productividad-departamento-chart').getContext('2d');
        new Chart(productividadDepartamentoCtx, {
            type: 'bar',
            data: {
                labels: departmentData.map(item => item.name),
                datasets: [{
                    label: 'Productividad',
                    data: departmentData.map(item => item.productividad),
                    backgroundColor: departmentData.map((_, index) => COLORS[index % COLORS.length])
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'pzs/pers/hora'
                        }
                    }
                }
            }
        });

        // 3. Gráfico de Tiempo Muerto por Departamento
        const tiempoMuertoCtx = document.getElementById('tiempo-muerto-chart').getContext('2d');
        new Chart(tiempoMuertoCtx, {
            type: 'bar',
            data: {
                labels: tiempoMuertoData.map(item => item.name),
                datasets: [{
                    label: '% Tiempo Muerto',
                    data: tiempoMuertoData.map(item => item.porcentaje),
                    backgroundColor: tiempoMuertoData.map((_, index) => COLORS[index % COLORS.length])
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '% Tiempo Muerto'
                        }
                    }
                }
            }
        });

        // 4. Gráfico de Distribución de Tiempo Muerto (Pie)
        const tiempoMuertoPieCtx = document.getElementById('tiempo-muerto-pie-chart').getContext('2d');
        new Chart(tiempoMuertoPieCtx, {
            type: 'pie',
            data: {
                labels: tiempoMuertoData.filter(item => item.value > 0).map(item => item.name),
                datasets: [{
                    label: 'Tiempo Muerto',
                    data: tiempoMuertoData.filter(item => item.value > 0).map(item => item.value),
                    backgroundColor: COLORS,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value}h (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // 5. Gráfico de Rendimiento Semanal
        const rendimientoSemanalCtx = document.getElementById('rendimiento-semanal-chart').getContext('2d');
        new Chart(rendimientoSemanalCtx, {
            type: 'line',
            data: {
                labels: weeklyProductivityData.map(item => item.day),
                datasets: [{
                    label: 'Rendimiento %',
                    data: weeklyProductivityData.map(item => item.rendimiento),
                    borderColor: COLORS[1],
                    backgroundColor: COLORS[1],
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Rendimiento %'
                        }
                    }
                }
            }
        });

        // 6. Gráfico de Rendimiento por Departamento
        const rendimientoDepartamentoCtx = document.getElementById('rendimiento-departamento-chart').getContext('2d');
        new Chart(rendimientoDepartamentoCtx, {
            type: 'bar',
            data: {
                labels: departmentData.map(item => item.name),
                datasets: [{
                    label: 'Rendimiento %',
                    data: departmentData.map(item => item.rendimiento),
                    backgroundColor: departmentData.map((_, index) => COLORS[index % COLORS.length])
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Rendimiento %'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>