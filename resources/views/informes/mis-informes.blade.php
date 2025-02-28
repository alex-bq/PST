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
    <div class="container mx-auto p-4">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-primary">Mis Informes</h1>
            <p>Bienvenido, {{ session('user')['nombre'] }}</p>
        </header>

        <!-- Sección de Informes Pendientes -->
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Informes Pendientes por Crear</h2>
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
                                            {{ $informe->Nomturno }}
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
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Informes Creados</h2>
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
                                            {{ $informe->Nomturno }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($informe->total_kilos_entrega, 1) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($informe->total_kilos_recepcion, 1) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('informes.show', ['fecha' => $informe->fec_turno, 'turno' => $informe->turno]) }}"
                                                class=class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
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
        <div class="mt-8 bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Búsqueda Histórica</h2>
                <div class="flex flex-wrap gap-4 mb-4">
                    <input type="date" name="fecha" id="fecha" class="flex-1 p-2 border rounded-md min-w-[200px]">
                    <select name="turno" id="turno" class="flex-1 p-2 border rounded-md min-w-[200px]">
                        <option value="">Todos los turnos</option>
                        @foreach($turnos as $turno)
                            <option value="{{ $turno->CodTurno }}">{{ $turno->NomTurno }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="searchButton"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        Buscar
                    </button>
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
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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
                const searchUrl = `${window.location.origin}/pst/public/informes/search?fecha=${fecha}&turno=${turno}`;
                console.log('URL de búsqueda:', searchUrl);

                // Mostrar indicador de carga
                resultsBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Buscando...</td></tr>';

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

                                const row = `
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${fechaFormateada}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${informe.NomTurno}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${informe.jefe_turno}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                    ${Number(informe.total_kilos_entrega).toFixed(1)}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                    ${Number(informe.total_kilos_recepcion).toFixed(1)}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="/pst/public/informes/detalle/${informe.fecha_turno}/${informe.turno}"
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
                                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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
                                            <td colspan="6" class="px-6 py-4 text-center text-red-500">
                                                Error al realizar la búsqueda: ${error.message}
                                            </td>
                                        </tr>
                                    `;
                    });
            });
        });
    </script>
@endsection