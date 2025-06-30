@extends('layouts.main-iframe')

@section('title', 'Mis Informes')

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
                    <h1 class="text-2xl font-bold text-gray-900">Mis Informes de Turno</h1>
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

        <!-- Sección de Informes Pendientes -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Informes Pendientes por Crear</h2>
                <p class="text-gray-600">Planillas guardadas de los últimos 7 días que requieren informe de turno</p>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('informes.crear', ['fecha' => $informe->fec_turno, 'turno' => $informe->turno]) }}"
                                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                                Crear Informe
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-blue-700">
                        <p>No hay informes pendientes por crear.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sección de Informes Creados -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Informes Creados</h2>
                <p class="text-gray-600">Informes de turno ya generados</p>
            </div>
            <div class="p-6">
                @if(count($informesCreados) > 0)
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
                                        Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha Creación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kilos Entrega</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kilos Recepción</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($informesCreados as $informe)
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
                                            @php
                                                // Mapear estado numérico a texto y clase CSS
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($informe->total_kilos_entrega, 1) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($informe->total_kilos_recepcion, 1) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('informes.show', ['fecha' => $informe->fec_turno, 'turno' => $informe->turno]) }}"
                                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                                Ver Detalle
                                            </a>
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
                        <p>No hay informes creados.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Búsqueda Histórica -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Búsqueda Histórica</h2>
                <p class="text-gray-600">Buscar informes por fecha y turno específicos</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                        <input type="date" name="fecha" id="fecha"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="turno" class="block text-sm font-medium text-gray-700 mb-1">Turno</label>
                        <select name="turno" id="turno"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos los turnos</option>
                            @foreach($turnos as $turno)
                                <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="searchButton"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                            Buscar
                        </button>
                    </div>
                </div>

                <!-- Resultados de búsqueda -->
                <div id="searchResults" class="mt-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="resultsTable">
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
                                        Kilos Entrega</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kilos Recepción</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="resultsBody">
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Use los filtros para buscar informes
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchButton = document.getElementById('searchButton');

            searchButton.addEventListener('click', function () {
                const fecha = document.getElementById('fecha').value;
                const turno = document.getElementById('turno').value;
                const resultsBody = document.getElementById('resultsBody');

                // Mostrar los parámetros de búsqueda en la consola
                console.log('Buscando con parámetros:', { fecha, turno });

                // Construir la URL
                const searchUrl = `${window.location.origin}/pst2/public/informes/search?fecha=${fecha}&turno=${turno}`;
                console.log('URL de búsqueda:', searchUrl);

                // Mostrar indicador de carga
                resultsBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">Buscando...</td></tr>';

                fetch(searchUrl)
                    .then(response => response.json())
                    .then(data => {
                        resultsBody.innerHTML = '';

                                                if (data && data.length > 0) {
                            data.forEach(informe => {
                                const fechaFormateada = new Date(informe.fecha_turno + 'T00:00:00').toLocaleDateString('es-CL', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                });

                                // Mapear estado numérico a texto y clase CSS
                                let estadoClase, estadoTexto;
                                switch(parseInt(informe.estado)) {
                                    case 1:
                                        estadoClase = 'bg-green-100 text-green-800';
                                        estadoTexto = 'Completado';
                                        break;
                                    case 0:
                                        estadoClase = 'bg-yellow-100 text-yellow-800';
                                        estadoTexto = 'Borrador';
                                        break;
                                    default:
                                        estadoClase = 'bg-gray-100 text-gray-800';
                                        estadoTexto = 'Desconocido';
                                        break;
                                }

                                const row = `
                                            <tr class="hover:bg-gray-50">
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
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                    ${Number(informe.total_kilos_entrega).toFixed(1)}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                    ${Number(informe.total_kilos_recepcion).toFixed(1)}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="/pst2/public/informes/detalle/${informe.fecha_turno}/${informe.turno}"
                                                        class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                                        Ver Detalle
                                                    </a>
                                                </td>
                                            </tr>
                                        `;
                                resultsBody.insertAdjacentHTML('beforeend', row);
                            });
                        } else {
                            resultsBody.innerHTML = `
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                                No se encontraron resultados
                                            </td>
                                        </tr>
                                    `;
                        }
                    })
                                        .catch(error => {
                        console.error('Error en la búsqueda:', error);
                        resultsBody.innerHTML = `
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-red-500">
                                            Error al realizar la búsqueda: ${error.message}
                                        </td>
                                    </tr>
                                `;
                    });
            });
        });
    </script>
@endsection