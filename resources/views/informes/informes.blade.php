@extends('layouts.main-iframe')

@section('title', 'Informes de Producción')

@section('styles')
    <style>
        /* Estilos generales */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Estilos del título */
        .page-title {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1rem;
        }

        /* Estilos del formulario de búsqueda */
        .search-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        /* Estilos de la tabla */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
        }

        .table tr:hover {
            background-color: #f8fafc;
        }

        /* Estilos del botón */
        .btn-search {
            background-color: #1a237e;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            background-color: rgb(20, 27, 97);
            transform: translateY(-1px);
        }

        .btn-search:active {
            transform: translateY(0);
        }

        .btn-detail {
            background-color: #1a237e;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            background-color: rgb(20, 27, 97);
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .search-form {
                flex-direction: column;
            }

            .form-group {
                width: 100%;
            }

            .table-container {
                overflow-x: auto;
            }
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1 class="page-title">Buscar Informes Diarios</h1>

        <div class="search-form">
            <div class="form-group">
                <input type="date" name="fecha" id="fecha" class="form-control">
            </div>
            <div class="form-group">
                <select name="turno" id="turno" class="form-control">
                    <option value="">Todos los turnos</option>
                    @foreach($turnos as $turno)
                        <option value="{{ $turno->CodTurno }}">{{ $turno->NomTurno }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" id="searchButton" class="btn-search">
                Buscar
            </button>
        </div>

        <div class="table-container mt-4">
            <table class="table" id="resultsTable">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Turno</th>
                        <th>Jefe de Turno</th>

                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="resultsBody">
                    <tr>
                        <td colspan="6" class="text-center text-gray-500">
                            Use los filtros para buscar informes
                        </td>
                    </tr>
                </tbody>
            </table>
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

                // Mostrar indicador de carga
                resultsBody.innerHTML = '<tr><td colspan="6" class="text-center">Buscando...</td></tr>';

                // Construir la URL
                const searchUrl = `${window.location.origin}/pst/public/informes/search?fecha=${fecha}&turno=${turno}`;

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
                                                        <tr>
                                                            <td>${fechaFormateada}</td>
                                                            <td>${informe.NomTurno}</td>
                                                            <td>${informe.jefe_turno}</td>

                                                            <td>
                                                                <a href="/pst/public/informes/detalle/${informe.fecha_turno}/${informe.turno}"
                                                                    class="btn-detail">
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
                                                        <td colspan="6" class="text-center">
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
                                                    <td colspan="6" class="text-center text-red-500">
                                                        Error al realizar la búsqueda: ${error.message}
                                                    </td>
                                                </tr>
                                            `;
                    });
            });
        });
    </script>
@endsection