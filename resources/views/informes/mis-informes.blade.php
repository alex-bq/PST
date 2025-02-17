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
                                            <a href="{{ route('informes.detalle', ['fecha' => $informe->fec_turno, 'turno' => $informe->turno]) }}"
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('informes.detalle', ['fecha' => $informe->fec_turno, 'turno' => $informe->turno]) }}"
                                                class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors duration-200">
                                                Ver Detalle
                                            </a>
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
        <div class="mt-8 bg-card text-card-foreground rounded-lg shadow-md">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Búsqueda Histórica</h2>
                <form action="" method="GET" class="flex space-x-2">
                    <input type="text" name="busqueda" placeholder="Buscar por fecha o turno"
                        class="flex-grow p-2 border rounded-md">
                    <button type="submit" class="btn-detail">
                        Buscar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection