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

<body>
    <nav class="navbar sticky-top navbar-expand" style="background-color: #fdfdfd;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/inicio') }}">
                <img src="{{ url('http://192.168.1.122/img/logo.png') }}" alt="Logo" height="50">
            </a>

            <!-- <div class="collapse navbar-collapse justify-content" id="navbarNavAltMarkup">
                <div class="navbar-nav d-flex align-items-center">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                    <a class="nav-link" href="#">Features</a>
                    <a class="nav-link" href="#">Pricing</a>
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </div>
            </div> -->

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
            <div class="accordion-container">

                <a type="button" class="accordion-titulo btn btn-light btn-sm" href='#'>Mas filtros<span
                        class="toggle-icon"></span></a>
                <div class="accordion-content">

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

                                    <input type="text" class="form-control" id="codLote" name="codLote"
                                        placeholder="Ingrese el lote" required>


                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="codEmpresa">Empresa</label>
                                    <select class="form-select" name="empresa">
                                        <option selected disabled></option>
                                        @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->cod_empresa }}">{{ $empresa->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="proveedor">Proveedor</label>
                                    <select class="form-select" name="proveedor">
                                        <option selected disabled></option>
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
                                        <option selected disabled></option>
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
                                        <option selected disabled>Seleccione un turno</option>
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
                                    <select class="form-select" name="supervisor" @if(session('user')['cod_rol']==2)
                                        disabled @endif>
                                        <option selected disabled>Seleccione un supervisor</option>
                                        @foreach ($supervisores as $supervisor)
                                        <option value="{{ $supervisor->cod_usuario }}" @if(session('user')['cod_rol']==2
                                            && session('user')['cod_usuario']==$supervisor->cod_usuario) selected
                                            @endif>
                                            {{ $supervisor->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codPlanillero">Planillero</label>
                                    <select class="form-select" name="planillero" @if(session('user')['cod_rol']==1)
                                        disabled @endif>
                                        <option selected disabled>Seleccione un planillero</option>
                                        @foreach ($planilleros as $planillero)
                                        <option value="{{ $planillero->cod_usuario }}" @if(session('user')['cod_rol']==1
                                            && session('user')['cod_usuario']==$planillero->cod_usuario) selected
                                            @endif>
                                            {{ $planillero->nombre }}
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
        $(function () {
            $(".accordion-titulo").click(function (e) {

                e.preventDefault();

                var contenido = $(this).next(".accordion-content");

                if (contenido.css("display") == "none") { //open		
                    contenido.slideDown(250);
                    $(this).addClass("open");
                }
                else { //close		
                    contenido.slideUp(250);
                    $(this).removeClass("open");
                }

            });
        });
    </script>
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
    <script>
        $(document).ready(function () {
            // Asociar el evento blur al campo de lote
            $('#codLote').on('blur', function () {
                // Obtén el valor del campo de lote
                var loteValue = $(this).val();

                // Realiza la consulta AJAX solo si el campo de lote tiene un valor
                if (loteValue.trim() !== '') {
                    // Realiza la consulta AJAX
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('obtener_valores_lote') }}',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'lote': loteValue
                        },
                        success: function (response) {
                            // Actualiza los valores de los select con la respuesta del servidor
                            $('select[name="empresa"]').val(response.cod_empresa)
                            $('select[name="proveedor"]').val(response.cod_proveedor)
                            $('select[name="especie"]').val(response.cod_especie)
                            $('#mensajeError').hide()
                        },
                        error: function (xhr) {
                            $('select[name="empresa"]').val('').prop('disabled', false);
                            $('select[name="proveedor"]').val('').prop('disabled', false);
                            $('select[name="especie"]').val('').prop('disabled', false);
                            $('#mensajeError').text('El lote no existe.').show();

                        }


                    });
                }
            });
        });
    </script>

</body>

</html>