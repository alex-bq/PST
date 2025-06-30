@extends('layouts.main-iframe')

@section('title', 'Crear Informe de Turno')

@section('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        /* Solo estilos específicos que Tailwind no puede manejar */
        .desarrollo-pendiente {
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            animation: pulse 2s infinite;
        }
    </style>
@endsection

@section('content')

    <body class="min-h-screen bg-gray-50">
        <!-- Header Moderno -->
        <div class="bg-white shadow-sm border-b sticky top-0 z-50">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button onclick="window.history.back()"
                            class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Volver
                        </button>
                        <div>
                            <h1 class="text-xl font-bold">Crear Informe de Turno</h1>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} - {{ $informe->turno }} - Jefe:
                                {{ $informe->jefe_turno_nom }}
                            </p>
                            <p class="text-xs text-blue-600">
                                @if(isset($informe->hora_inicio) && isset($informe->hora_termino))
                                    {{ $informe->hora_inicio }} - {{ $informe->hora_termino }}
                                    @if(isset($informe->horas_trabajadas))
                                        ({{ number_format($informe->horas_trabajadas, 1) }}h)
                                    @endif
                                @else
                                    <span class="desarrollo-pendiente">HORARIOS EN DESARROLLO</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <button onclick="guardarInforme()"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        Guardar Informe
                    </button>
                </div>
            </div>
        </div>

        <div class="container mx-auto p-6 space-y-6">
            @php
                // Reorganizar datos por Sala -> Proceso -> Empresa (simulado)
                $salas_agrupadas = collect($informacion_sala)->groupBy('nombre_sala');
            @endphp

            @foreach($salas_agrupadas as $sala_nombre => $datos_sala)
                <!-- Card de Sala -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold">{{ $sala_nombre }}</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($datos_sala as $sala)
                            <!-- Proceso -->
                            <div class="border rounded-lg p-4 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-semibold text-lg">{{ $sala->tipo_planilla }}</h3>
                                        <div class="flex gap-2 mt-1">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-md text-sm">
                                                <span class="desarrollo-pendiente">EMPRESAS EN DESARROLLO</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empresa simulada (usando datos existentes) -->
                                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium text-gray-900 flex items-center gap-2">
                                            <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-md text-sm">
                                                Datos Consolidados <span class="desarrollo-pendiente">EMPRESA</span>
                                            </span>
                                        </h4>
                                        <div class="flex gap-2">
                                            <button onclick="mostrarModal('planillas-{{ $sala->cod_sala }}')"
                                                class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                                <i data-lucide="file-text" class="h-4 w-4"></i>
                                                Planillas
                                            </button>
                                            <button onclick="mostrarModal('productos-{{ $sala->cod_sala }}')"
                                                class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                                <i data-lucide="package" class="h-4 w-4"></i>
                                                Productos
                                            </button>
                                            <button onclick="mostrarModal('tiempos-{{ $sala->cod_sala }}')"
                                                class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                                <i data-lucide="clock" class="h-4 w-4"></i>
                                                Tiempos Muertos
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Grid de información operacional -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div class="bg-white p-3 rounded text-center">
                                            <p class="text-xs text-gray-600">Dotación</p>
                                            <input type="number" class="text-center font-semibold bg-transparent border-0 w-full"
                                                value="0" min="0" data-sala-id="{{ $sala->cod_sala }}">
                                        </div>
                                        <div class="bg-white p-3 rounded text-center">
                                            <p class="text-xs text-gray-600">Horas Reales</p>
                                            <p class="font-semibold">
                                                @if(isset($sala->horas_trabajadas))
                                                    {{ number_format($sala->horas_trabajadas, 1) }}h
                                                @else
                                                    <span class="desarrollo-pendiente">PENDIENTE</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="bg-white p-3 rounded text-center">
                                            <p class="text-xs text-gray-600">Entrega MP</p>
                                            <p class="font-semibold">{{ number_format($sala->kilos_entrega_total ?? 0, 0) }} kg</p>
                                        </div>
                                        <div class="bg-white p-3 rounded text-center">
                                            <p class="text-xs text-gray-600">Recepción Total</p>
                                            <p class="font-semibold">{{ number_format($sala->kilos_recepcion_total ?? 0, 0) }} kg
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Productividad Moderna (Grid 4x2) -->
                                    <div class="space-y-3">
                                        <h5 class="text-sm font-medium text-gray-700">
                                            Productividad ({{ $sala->tipo_planilla == 'Porciones' ? 'kg' : 'pzs' }}/persona/hora)
                                        </h5>

                                        <!-- Títulos de los grupos -->
                                        <div class="grid grid-cols-4 gap-3">
                                            <div class="col-span-2 text-center">
                                                <h6 class="text-xs font-medium text-gray-600 border-b pb-1">Productividad Total</h6>
                                            </div>
                                            <div class="col-span-2 text-center">
                                                <h6 class="text-xs font-medium text-gray-600 border-b pb-1">Productividad Objetivo
                                                </h6>
                                            </div>
                                        </div>

                                        <!-- Valores en una sola fila -->
                                        <div class="grid grid-cols-4 gap-3">
                                            <div class="bg-blue-50 p-2 rounded text-center">
                                                <p class="text-xs text-gray-600">Real Total</p>
                                                <p class="font-semibold text-blue-700">
                                                    <span class="desarrollo-pendiente">CALC</span>
                                                </p>
                                            </div>
                                            <div class="bg-blue-50 p-2 rounded text-center">
                                                <p class="text-xs text-gray-600">Efectiva Total</p>
                                                <p class="font-semibold text-blue-700">
                                                    <span class="desarrollo-pendiente">CALC</span>
                                                </p>
                                            </div>
                                            <div class="bg-green-50 p-2 rounded text-center">
                                                <p class="text-xs text-gray-600">Real Objetivo</p>
                                                <p class="font-semibold text-green-700">
                                                    <span class="desarrollo-pendiente">CALC</span>
                                                </p>
                                            </div>
                                            <div class="bg-green-50 p-2 rounded text-center">
                                                <p class="text-xs text-gray-600">Efectiva Objetivo</p>
                                                <p class="font-semibold text-green-700">
                                                    <span class="desarrollo-pendiente">CALC</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Recepción de producto -->
                                    <div class="space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700">Recepción de Producto (kg)</h5>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="bg-white p-2 rounded text-center">
                                                <p class="text-xs text-gray-600">PST Objetivo</p>
                                                <p class="font-semibold">
                                                    <span class="desarrollo-pendiente">PENDIENTE</span>
                                                </p>
                                            </div>
                                            <div class="bg-white p-2 rounded text-center">
                                                <p class="text-xs text-gray-600">PST Total</p>
                                                <p class="font-semibold">{{ number_format($sala->kilos_recepcion_total ?? 0, 0) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Comentarios y Fotos por Sala -->
                        <div class="mt-6 space-y-4 border-t pt-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Comentarios de {{ $sala_nombre }}</label>
                                <textarea name="comentarios_sala[{{ $sala_nombre }}]"
                                    placeholder="Observaciones específicas de {{ $sala_nombre }}..."
                                    class="w-full px-3 py-2 border-2 border-blue-200 rounded-md focus:border-blue-400 focus:outline-none"
                                    rows="3"></textarea>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button"
                                    class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                    <i data-lucide="camera" class="h-4 w-4"></i>
                                    Adjuntar Fotos
                                    <span class="desarrollo-pendiente">EN DESARROLLO</span>
                                </button>
                                <span class="text-sm text-gray-500">0 fotos adjuntas</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if(count($empaque_premium) > 0)
                <!-- Sección Empaque (mantener estructura actual pero con Tailwind) -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold">Empaque Premium</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Dotación Real</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md" min="0"
                                    value="0">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Horas Trabajadas</label>
                                <div class="flex gap-2">
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md" min="0"
                                        value="0" placeholder="H">
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md" min="0"
                                        max="59" value="0" placeholder="M">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Productividad</label>
                                <div class="text-lg font-bold text-blue-600">
                                    <span class="desarrollo-pendiente">CÁLCULO AUTOMÁTICO</span>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empresa</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Lotes</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Kilos</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Piezas</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($empaque_premium as $premium)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $premium->Producto }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $premium->Empresa }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                {{ $premium->Cantidad_Lotes }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                {{ number_format($premium->Total_Kilos, 1) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                {{ number_format($premium->Total_Piezas, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Comentarios Generales -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Comentarios Generales del Turno</h2>
                    <p class="text-gray-600">Observaciones generales, incidencias y logros destacados</p>
                </div>
                <div class="p-6">
                    <textarea name="comentarios_turno" id="comentarios_turno"
                        placeholder="Observaciones generales del turno, incidencias, logros destacados, coordinaciones especiales..."
                        class="w-full px-3 py-2 border-2 border-blue-200 rounded-md focus:border-blue-400 focus:outline-none"
                        rows="4"></textarea>
                </div>
            </div>

            <!-- Botón Guardar -->
            <div class="text-center">
                <button onclick="guardarInforme()"
                    class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="save" class="h-5 w-5 inline mr-2"></i>
                    Guardar y Confirmar Informe
                </button>
            </div>
        </div>

        <!-- Modal para mostrar detalles -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg max-w-4xl max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 id="modal-title" class="text-lg font-semibold"></h3>
                    <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>
                <div id="modal-content" class="p-6">
                    <div class="text-center text-gray-500 py-8">
                        <i data-lucide="construction" class="h-16 w-16 mx-auto mb-4 text-yellow-500"></i>
                        <h3 class="text-lg font-semibold mb-2">Funcionalidad en Desarrollo</h3>
                        <p>Los modales de detalle están siendo implementados.</p>
                        <p class="text-sm">Próximamente: Planillas, Productos y Tiempos Muertos detallados.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Inicializar Lucide Icons
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });

        function guardarInforme() {
            // Función existente mantenida
            alert('Guardando informe... (funcionalidad en desarrollo)');
        }

        function mostrarModal(tipo) {
            const modal = document.getElementById('modal');
            const title = document.getElementById('modal-title');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (tipo.includes('planillas')) {
                title.textContent = 'Desglose por Planillas';
            } else if (tipo.includes('productos')) {
                title.textContent = 'Desglose por Productos';
            } else if (tipo.includes('tiempos')) {
                title.textContent = 'Análisis de Tiempos Muertos';
            }

            lucide.createIcons();
        }

        function cerrarModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modal').addEventListener('click', function (e) {
            if (e.target === this) {
                cerrarModal();
            }
        });
    </script>
@endsection