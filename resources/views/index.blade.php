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


<nav class="navbar sticky-top navbar-expand  " style="background-color: #fdfdfd;">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/inicio') }}"><img src="{{ asset('image/logo.svg') }}" alt="Logo" height="50" ></a>

        <div class="collapse navbar-collapse justify-content" id="navbarNavAltMarkup">
            <div class="navbar-nav d-flex align-items-center">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
                <a class="nav-link" href="#">Features</a>
                <a class="nav-link" href="#">Pricing</a>
                <a class="nav-link disabled" aria-disabled="true">Disabled</a>
            </div>
        </div>

        

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="navbar-nav d-flex align-items-center">
                <label class="nav-item nav-link">{{ session('user')['nombre'] }}</label>

                <label class="nav-item nav-link">Rol: {{ session('user')['rol'] }}</label>

                <label class="nav-item nav-link"><button type="button" class="btn btn-light btn-sm"  data-bs-href="{{ url('/logout') }}">Cerrar sesión</button></label>
            </div>
        </div>
    </div>
</nav>

<body>
    <div class="container">
        <h1 class="mb-4">Planillas</h1>


            <div class="mb-3 d-flex justify-content-between">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-href="{{ url('/nueva-planilla') }}">Nueva Planilla</button>

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
    </div>


    <!-- modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Crear nueva planilla</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body mx-auto">
                    <form id="formularioPlanilla" action="{{ route('procesar.formulario') }}" method="post" >
                    @csrf
                        <div class='row'>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div id="mensajeError" class="alert alert-danger" style="display: none;"></div>
                                    <label for="codLote">Lote</label>
                                    <input type="text" class="form-control" id="codLote" name="codLote" required>
                                </div>
                            </div>
                        </div>
                        <!-- Fila 1 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codEmpresa">Empresa</label>
                                    <select class="form-select" name="empresa">
                                        <option selected></option>
                                        @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->cod_empresa }}">{{ $empresa->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codEspecie">Especie</label>
                                    <select class="form-select" name="especie" >
                                        <option selected></option>
                                        @foreach ($especies as $especie)
                                            <option value="{{ $especie->cod_especie }}">{{ $especie->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Fila 2 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fechaTurno">Fecha de Turno</label>
                                    <input type="date" class="form-control" id="fechaTurno" name="fechaTurno" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codTurno">Turno</label>
                                    <select class="form-select" name="turno" >
                                        <option selected></option>
                                        @foreach ($turnos as $turno)
                                            <option value="{{ $turno->codTurno }}">{{ $turno->NomTurno }}</option>
                                        @endforeach
                                    </select >
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codSupervisor">Supervisor</label>
                                    <select class="form-select" name="supervisor" >
                                        <option selected></option>
                                        @foreach ($supervisores as $supervisor)
                                            <option value="{{ $supervisor->cod_usuario }}">{{ $supervisor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codPlanillero">Planillero</label>
                                    
                                    <select class="form-select" name="planillero" >
                                        <option selected></option>
                                        @foreach ($planilleros as $planillero)
                                            <option value="{{ $planillero->cod_usuario }}">{{ $planillero->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <!-- Botón de Enviar -->
                        <button type="submit" class="btn btn-primary">Crear Planilla</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y otros scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Tus scripts adicionales -->
    <script>
    $(document).ready(function () {
        // Intercepta el envío del formulario
        $('#formularioPlanilla').submit(function (event) {
            // Evita que el formulario se envíe normalmente
            event.preventDefault();

            // Realiza la solicitud AJAX
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function (response) {
                    // Maneja la respuesta exitosa, si es necesario
                    console.log(response);
                },
                error: function (xhr) {
                    // Maneja la respuesta de error
                    if (xhr.status === 419) {
                        // Error CSRF, puedes manejarlo como desees
                        console.error('Error CSRF');
                    } else {
                        // Error de lote no existente
                        $('#mensajeError').text('El lote no existe.').show();
                    }
                }
            });
        });
    });
</script>
</body>

</html>
