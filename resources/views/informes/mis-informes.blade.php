@extends('layouts.main-iframe')

@section('title', 'Informes de Turno')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .btn-detail {
            @apply bg-primary text-white px-3 py-2 rounded-md hover:bg-primary-dark transition-colors duration-300;
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto p-6 space-y-8">
        <!-- Header Moderno -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Informes de Turno</h1>
                    <p class="text-gray-600">Bienvenido, {{ session('user')['nombre'] }}
                        {{ session('user')['apellido'] ?? '' }} -
                        @if(session('user')['cod_rol'] == 3)
                            Administrador
                        @elseif(session('user')['cod_rol'] == 4)
                            Jefe de Turno
                        @else
                            Usuario
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 1: Informes Pendientes por Crear -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b">
                @if(session('user')['cod_rol'] == 3)
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">📋 Informes Pendientes por Crear (Supervisión)</h2>
                    <p class="text-gray-600">Planillas guardadas de los últimos 7 días que requieren informe de turno - Solo
                        visualización</p>
                @else
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">📋 Informes Pendientes por Crear</h2>
                    <p class="text-gray-600">Planillas guardadas de los últimos 7 días que requieren informe de turno</p>
                @endif
            </div>
            <div class="p-6">
                @if(count($informesPendientes) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Turno</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jefe de Turno</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad Planillas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kilos Entrega</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kilos Recepción</th>
                                    @if(session('user')['cod_rol'] != 3)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($informesPendientes as $informe)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($informe->fec_turno)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $informe->nombre_turno }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $informe->jefe_turno }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $informe->cantidad_planillas }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($informe->total_kilos_entrega, 1) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($informe->total_kilos_recepcion, 1) }}
                                        </td>
                                        @if(session('user')['cod_rol'] != 3)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('informes.crearBorrador', ['fecha' => $informe->fec_turno, 'turno' => $informe->turno]) }}"
                                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                                    Crear Informe
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-blue-700">
                        @if(session('user')['cod_rol'] == 3)
                            <p>No hay informes pendientes por crear en el sistema.</p>
                        @else
                            <p>No hay informes pendientes por crear.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if(session('user')['cod_rol'] != 3)
            <!-- SECCIÓN 2: Mis Informes Creados - Solo para Jefes de Turno -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">✅ Mis Informes Creados</h2>
                    <p class="text-gray-600">Informes de turno que he creado (últimos 7 días)</p>
                </div>
                <div class="p-6">
                    @if(count($misInformesCreados) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Turno</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha Creación</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($misInformesCreados as $informe)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($informe->fec_turno)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $informe->nombre_turno }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @php
                                                    switch ($informe->estado) {
                                                        case 1:
                                                            $estadoClase = 'bg-green-100 text-green-800';
                                                            $estadoTexto = 'Completado';
                                                            break;
                                                        case 0:
                                                            $estadoClase = 'bg-yellow-100 text-yellow-800';
                                                            $estadoTexto = 'Borrador';
                                                            break;
                                                        default:
                                                            $estadoClase = 'bg-gray-100 text-gray-800';
                                                            $estadoTexto = 'Desconocido';
                                                            break;
                                                    }
                                                @endphp
                                                <span class="px-2 py-1 {{ $estadoClase }} rounded-md text-sm font-medium">
                                                    {{ $estadoTexto }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $informe->fecha_creacion_formatted ?? 'No disponible' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                @if($informe->estado == 0)
                                                    <a href="{{ route('informes.editar', ['cod_informe' => $informe->cod_informe]) }}"
                                                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                                        Continuar Editando
                                                    </a>
                                                @else
                                                    <a href="{{ route('informes.show', ['fecha' => $informe->fec_turno, 'turno' => $informe->turno]) }}"
                                                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                                        Ver Detalle
                                                    </a>
                                                @endif
                                                <form action="{{ route('informes.destroy', $informe->cod_informe) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('¿Está seguro de eliminar este informe?')"
                                                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-colors duration-200">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-blue-700">
                            <p>No tienes informes creados en los últimos 7 días.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- SECCIÓN 3: Buscador General de Informes -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">🔍 Buscar InformesCompletados</h2>
                <p class="text-gray-600">Consultar informes completados del sistema con filtros</p>

            </div>
            <div class="p-6">
                <!-- Filtros de búsqueda -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <input type="date" id="buscar_fecha"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                placeholder="dd-mm-aaaa">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Turno</label>
                            <select id="buscar_turno"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos los turnos</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jefe de Turno</label>
                            <select id="buscar_jefe_turno"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos los jefes</option>
                                <!-- Se llenará dinámicamente -->
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button onclick="buscarInformes()"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                Buscar
                            </button>
                            <button onclick="limpiarBusqueda()"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors duration-200">
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resultados de búsqueda -->
                <div id="resultados_busqueda" class="hidden">
                    <div class="mb-4">
                        <p id="contador_resultados" class="text-sm text-gray-600"></p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Turno</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jefe de Turno</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha Creación</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla_resultados" class="bg-white divide-y divide-gray-200">
                                <!-- Los resultados se cargarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mensaje cuando no hay búsqueda -->
                <div id="mensaje_inicial" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 text-yellow-700">
                    <p>Informes completados de los últimos 3 meses.</p>
                    <p class="text-sm mt-1">Usa los filtros de arriba para buscar informes específicos.</p>
                </div>

                <!-- Mensaje cuando no hay resultados -->
                <div id="sin_resultados" class="hidden bg-blue-50 border-l-4 border-blue-400 p-4 text-blue-700">
                    <p>No se encontraron informes completados con los filtros especificados.</p>
                    <p class="text-sm mt-1">Intenta modificar los criterios de búsqueda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para funcionalidad de búsqueda -->
    <script>
        // URLs de Laravel generadas dinámicamente
        const URLS = {
            jefesTurno: "{{ route('api.jefes-turno') }}",
            search: "{{ route('informes.search') }}",
            editarInforme: "{{ url('/informes/editar') }}",
            verDetalle: "{{ url('/informes/detalle') }}"
        };

        // Cargar jefes de turno al cargar la página
        document.addEventListener('DOMContentLoaded', function () {
            cargarJefesTurno().then(() => {
                // Ejecutar búsqueda automática después de cargar jefes de turno
                buscarInformes(true); // true = búsqueda automática
            });
        });

        // Función para cargar jefes de turno
        async function cargarJefesTurno() {
            try {
                console.log('Cargando jefes de turno desde:', URLS.jefesTurno);

                const response = await fetch(URLS.jefesTurno);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const jefes = await response.json();

                const select = document.getElementById('buscar_jefe_turno');

                // Limpiar opciones existentes (excepto la primera)
                select.innerHTML = '<option value="">Todos los jefes</option>';

                // Verificar si hay datos
                if (Array.isArray(jefes) && jefes.length > 0) {
                    // Agregar opciones de jefes
                    jefes.forEach(jefe => {
                        const option = document.createElement('option');
                        option.value = jefe.cod_usuario;
                        option.textContent = jefe.nombre_completo;
                        select.appendChild(option);
                    });
                    console.log('Jefes de turno cargados:', jefes.length);
                } else {
                    console.log('No se encontraron jefes de turno');
                }

            } catch (error) {
                console.error('Error al cargar jefes de turno:', error);
                // Mostrar mensaje de error al usuario
                const select = document.getElementById('buscar_jefe_turno');
                select.innerHTML = '<option value="">Error cargando jefes</option>';
            }
        }

        // Función principal de búsqueda
        async function buscarInformes(esAutomatica = false) {
            try {
                // Ocultar mensajes existentes
                document.getElementById('sin_resultados').classList.add('hidden');
                document.getElementById('resultados_busqueda').classList.add('hidden');

                if (!esAutomatica) {
                    // Solo ocultar mensaje inicial si NO es búsqueda automática
                    document.getElementById('mensaje_inicial').classList.add('hidden');
                }

                // Obtener valores de los filtros
                const fecha = document.getElementById('buscar_fecha').value;
                const turno = document.getElementById('buscar_turno').value;
                const jefeturno = document.getElementById('buscar_jefe_turno').value;

                // Construir parámetros de consulta
                const params = new URLSearchParams();
                if (fecha) params.append('fecha', fecha);
                if (turno) params.append('turno', turno);
                if (jefeturno) params.append('jefe_turno', jefeturno);

                console.log('Parámetros de búsqueda:', { fecha, turno, jefeturno });

                // Realizar petición
                const searchUrl = `${URLS.search}?${params.toString()}`;
                console.log('URL de búsqueda:', searchUrl);

                const response = await fetch(searchUrl);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const resultados = await response.json();

                console.log('Resultados obtenidos:', resultados.length);

                // Mostrar resultados
                mostrarResultados(resultados, esAutomatica);

            } catch (error) {
                console.error('Error en búsqueda:', error);
                alert('Error al realizar la búsqueda. Por favor, intenta nuevamente.');
            }
        }

        // Función para mostrar los resultados en la tabla
        function mostrarResultados(resultados, esAutomatica) {
            const tablaResultados = document.getElementById('tabla_resultados');
            const contadorResultados = document.getElementById('contador_resultados');
            const divResultados = document.getElementById('resultados_busqueda');
            const sinResultados = document.getElementById('sin_resultados');
            const mensajeInicial = document.getElementById('mensaje_inicial');

            // Limpiar tabla
            tablaResultados.innerHTML = '';

            if (resultados.length === 0) {
                // Si es búsqueda automática y no hay resultados, mostrar mensaje inicial
                if (esAutomatica) {
                    mensajeInicial.classList.remove('hidden');
                    divResultados.classList.add('hidden');
                } else {
                    // Si es búsqueda manual, mostrar mensaje de sin resultados
                    sinResultados.classList.remove('hidden');
                    divResultados.classList.add('hidden');
                }
                return;
            }

            // Si hay resultados, ocultar mensaje inicial y mostrar tabla
            mensajeInicial.classList.add('hidden');

            // Actualizar contador
            contadorResultados.textContent = `Se encontraron ${resultados.length} informes completados`;

            // Llenar tabla con resultados
            resultados.forEach(informe => {
                const fila = document.createElement('tr');
                fila.className = 'hover:bg-gray-50';

                // Determinar estado y clase
                let estadoTexto, estadoClase;
                switch (parseInt(informe.estado)) {
                    case 1:
                        estadoTexto = 'Completado';
                        estadoClase = 'bg-green-100 text-green-800';
                        break;
                    case 0:
                        estadoTexto = 'Borrador';
                        estadoClase = 'bg-yellow-100 text-yellow-800';
                        break;
                    default:
                        estadoTexto = 'Desconocido';
                        estadoClase = 'bg-gray-100 text-gray-800';
                        break;
                }

                // Formatear fecha evitando problemas de zona horaria
                // Parsear la fecha manualmente para evitar conversiones de UTC
                const fechaParts = informe.fecha_turno.split('-');
                const fechaFormateada = `${fechaParts[2].padStart(2, '0')}/${fechaParts[1].padStart(2, '0')}/${fechaParts[0]}`;

                // Determinar botón de acción
                let botonAccion;
                if (parseInt(informe.estado) === 0) {
                    botonAccion = `
                                                                        <a href="${URLS.editarInforme}/${informe.cod_informe}" 
                                                                           class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors duration-200">
                                                                            Continuar Editando
                                                                        </a>
                                                                    `;
                } else {
                    botonAccion = `
                                                                        <a href="${URLS.verDetalle}/${informe.fecha_turno}/${informe.turno}" 
                                                                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                                                            Ver Detalle
                                                                        </a>
                                                                    `;
                }

                fila.innerHTML = `
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        ${fechaFormateada}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        ${informe.nombre}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        ${informe.jefe_turno}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        <span class="px-2 py-1 ${estadoClase} rounded-md text-sm font-medium">
                                                                            ${estadoTexto}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        ${informe.fecha_creacion_formatted || 'No disponible'}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                        ${botonAccion}
                                                                    </td>
                                                                `;

                tablaResultados.appendChild(fila);
            });

            // Mostrar tabla de resultados
            divResultados.classList.remove('hidden');
        }

        // Función para limpiar búsqueda
        function limpiarBusqueda() {
            document.getElementById('buscar_fecha').value = '';
            document.getElementById('buscar_turno').value = '';
            document.getElementById('buscar_jefe_turno').value = '';

            document.getElementById('resultados_busqueda').classList.add('hidden');
            document.getElementById('sin_resultados').classList.add('hidden');

            // Ejecutar búsqueda automática después de limpiar
            buscarInformes(true);
        }

        // Permitir búsqueda con Enter
        document.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                buscarInformes();
            }
        });
    </script>
@endsection