<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Página de Inicio</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
    <nav class="navbar sticky-top navbar-expand" style="background-color: #fdfdfd;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/inicio') }}">
                <img src="{{ url('http://192.168.1.122/img/logo.png') }}" alt="Logo" height="50">
            </a>

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
                    <a type="button" class="btn btn-light btn-sm" href="{{ url('/logout') }}">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">


        <div class="mb-3">
            <div class="row d-flex justify-content-between align-items-center">
                <h1 class="mb-4">Planillas</h1>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="filtroLote" placeholder="Filtrar por Lote">
                </div>

                <div class="col-md-6 text-end">
                    <!-- Utiliza la clase text-end para alinear el botón a la derecha -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        data-bs-href="{{ url('/nueva-planilla') }}">Nueva Planilla</button>
                </div>
            </div>
        </div>






        <div class="mb-3">
            <div class="accordion flush" id="filtroAcordeon">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="filtroHeader">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filtroCollapse" aria-expanded="true" aria-controls="filtroCollapse">
                            Filtros
                        </button>
                    </h2>
                    <div id="filtroCollapse" class="accordion-collapse collapse " aria-labelledby="filtroHeader"
                        data-bs-parent="#filtroAcordeon">
                        <div class="accordion-body">
                            <form>
                                <div class="row">

                                    <!-- Filtro por Fecha -->
                                    <div class="col-md-3">
                                        <input type="date" class="form-control" name="filtroFecha"
                                            placeholder="Filtrar por Fecha">
                                    </div>

                                    <!-- Otros filtros aquí... -->
                                    <div class="col-md-3">
                                        <select class="form-select" name="filtroTurno">
                                            <option selected disabled>Filtro Turno </option>
                                            @foreach ($turnos as $turno)
                                            <option value="{{ $turno->NomTurno }}">{{ $turno->NomTurno }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <select class="form-select" name="filtroProv">
                                            <option selected disabled>Filtro Proveedor</option>
                                            @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->cod_empresa }}">{{ $empresa->descripcion }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Filtrar</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @if(count($planillas) > 0)
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Lote</th>
                    <th>Fecha Turno</th>
                    <th>Turno</th>
                    <th>Empresa</th>
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
                    <td>{{ $planilla->proveedor }}</td>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Crear nueva planilla</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mx-auto">
                    <form id="formularioPlanilla" action="{{ route('procesar.formulario') }}" method="post">
                        @csrf
                        <div class='row'>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div id="mensajeError" class="alert alert-danger" style="display: none;"></div>
                                    <label for="codLote">Lote</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="codLote" name="codLote" required>
                                        <button class="btn btn-outline-success" type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z" />
                                                <path
                                                    d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="codEspecie">Proveedor</label>
                                    <select class="form-select" name="especie">
                                        <option selected></option>
                                        @foreach ($proveedores as $proveedor)
                                        <option value="{{ $proveedor->cod_proveedor }}">{{ $proveedor->descripcion }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="codEspecie">Especie</label>
                                    <select class="form-select" name="especie">
                                        <option selected></option>
                                        @foreach ($especies as $especie)
                                        <option value="{{ $especie->cod_especie }}">{{ $especie->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>

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
                                    <select class="form-select" name="turno">
                                        <option selected></option>
                                        @foreach ($turnos as $turno)
                                        <option value="{{ $turno->codTurno }}">{{ $turno->NomTurno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codSupervisor">Supervisor</label>
                                    <select class="form-select" name="supervisor">
                                        <option selected></option>
                                        @foreach ($supervisores as $supervisor)
                                        <option value="{{ $supervisor->cod_usuario }}">{{ $supervisor->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codPlanillero">Planillero</label>
                                    <select class="form-select" name="planillero">
                                        <option selected></option>
                                        @foreach ($planilleros as $planillero)
                                        <option value="{{ $planillero->cod_usuario }}">{{ $planillero->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br><br>

                        <button type="submit" class="btn btn-primary">Crear Planilla</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        var baseUrl = "{{ url('/') }}";

        $(document).ready(function () {
            $('#formularioPlanilla').submit(function (event) {
                event.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/procesar-formulario',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.redirect) {
                            window.location.href = baseUrl + response.redirect;
                        } else {
                            console.log(response.message);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 419) {
                            console.error('Error CSRF');
                        } else {
                            $('#mensajeError').text('El lote no existe.').show();
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>