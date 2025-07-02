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

        <!-- SECCIÓN 1: Informes Pendientes por Crear -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">📋 Informes Pendientes por Crear</h2>
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
                                            <a href="{{ route('informes.crearBorrador', ['fecha' => $informe->fec_turno, 'turno' => $informe->turno]) }}"
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

        <!-- SECCIÓN 2: Mis Informes Creados -->
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
                                                    class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors duration-200">
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

        <!-- SECCIÓN 3: Buscador General de Informes -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">🔍 Buscar Todos los Informes</h2>
                <p class="text-gray-600">Consultar informes de todo el sistema con filtros avanzados</p>
            </div>
            <div class="p-6">
                <!-- Filtros de búsqueda -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <input type="date" id="buscar_fecha"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select id="buscar_estado"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos los estados</option>
                                <option value="1">Completado</option>
                                <option value="0">Borrador</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button onclick="buscarInformes()"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resultados de búsqueda -->
                <div id="resultados_busqueda" class="hidden">
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
                    <p>💡 Utiliza los filtros de arriba para buscar informes en todo el sistema.</p>
                    <p class="text-sm mt-1">Si no especificas fecha, se mostrarán los informes de los últimos 3 meses.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Script básico para futura funcionalidad -->
    <script>
        function buscarInformes() {
            // TODO: Implementar funcionalidad de búsqueda
            console.log('Función de búsqueda - Por implementar');

            // Por ahora solo mostrar un mensaje
            alert('Funcionalidad de búsqueda en desarrollo');
        }

        // TODO: Cargar jefes de turno para el select
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Página cargada - Buscador listo para implementación');
        });
    </script>
@endsection