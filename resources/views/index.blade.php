<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Página de Inicio</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

</head>

@if(!session('user'))
    <script>
        window.location.href = "{{ url('/login') }}";
    </script>
@endif

<body>
    <div class="container">
        <h1 class="mb-4">Página de Inicio</h1>

        @if(session('user'))
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2>{{ session('user')['nombre'] }}</h2>
                    
                    <p>Rol: {{ session('user')['rol'] }}</p>
                    
                    <p><a href="{{ url('/logout') }}">Cerrar sesión</a></p>
                </div>
                
            </div>
            <h2>Planillas</h2>

            <div class="mb-3 d-flex justify-content-between">
                <a href="{{ url('/crear_planilla') }}" class="btn btn-primary">Crear Nueva Planilla</a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Nueva Planilla</button>

                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Buscar planillas" aria-label="Buscar">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </form>
            </div>


            @if(count($planillas) > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Lote</th>
                            <th>Fecha Turno</th>
                            <th>Turno</th>
                            <th>Proveedor</th>
                            <th>Especie</th>
                            <th>Supervisor</th>
                            <th>Planillero</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($planillas as $planilla)
                            <tr class="table-row" onclick="window.location='{{ url('/planilla/' . $planilla->cod_planilla) }}';">
                                <td>{{ $planilla->lote }}</td>
                                <td>{{ date('d/m/Y', strtotime($planilla->fec_turno)) }}</td>
                                <td>{{ $planilla->turno }}</td>
                                <td>{{ $planilla->empresa }}</td>
                                <td>{{ $planilla->especie }}</td>
                                <td>{{ $planilla->supervisor_nombre }}</td>
                                <td>{{ $planilla->planillero_nombre }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No hay datos de planilla disponibles.</p>
            @endif
        @endif
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="procesar_formulario.php" method="post">
        <!-- Cod Lote -->
        <div class="form-group">
            <label for="codLote">Código de Lote</label>
            <input type="text" class="form-control" id="codLote" name="codLote" required>
        </div>
        <!-- Fecha de Turno -->
        <div class="form-group">
            <label for="fechaTurno">Fecha de Turno</label>
            <input type="date" class="form-control" id="fechaTurno" name="fechaTurno" required>
        </div>
        <!-- Código de Turno -->
        <div class="form-group">
            <label for="codTurno">Código de Turno</label>
            <input type="text" class="form-control" id="codTurno" name="codTurno" required>
        </div>
        <!-- Código de Empresa -->
        <div class="form-group">
            <label for="codEmpresa">Código de Empresa</label>
            <input type="text" class="form-control" id="codEmpresa" name="codEmpresa" required>
        </div>
        <!-- Código de Especie -->
        <div class="form-group">
            <label for="codEspecie">Código de Especie</label>
            <input type="text" class="form-control" id="codEspecie" name="codEspecie" required>
        </div>
        <!-- Código de Planillero -->
        <div class="form-group">
            <label for="codPlanillero">Código de Planillero</label>
            <input type="text" class="form-control" id="codPlanillero" name="codPlanillero" required>
        </div>
        <!-- Código de Supervisor -->
        <div class="form-group">
            <label for="codSupervisor">Código de Supervisor</label>
            <input type="text" class="form-control" id="codSupervisor" name="codSupervisor" required>
        </div>
        <!-- Guardado -->
        <div class="form-group">
            <label for="guardado">Guardado</label>
            <input type="text" class="form-control" id="guardado" name="guardado" required>
        </div>

        <!-- Botón de Enviar -->
        <button type="submit" class="btn btn-primary">Crear Planilla</button>
    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y otros scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tus scripts adicionales -->
</body>

</html>
