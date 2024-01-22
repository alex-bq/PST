<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Página de Inicio</title>

    <!-- Incluye Bootstrap CSS y Select2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">


    <!-- Incluye jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Incluye Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Tu archivo de estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script src="{{ asset('js/index.js') }}"></script>

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
                    <input type="text" class="form-control" id="filtroLote" name="filtroLote"
                        placeholder="Filtrar por Lote">
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

                <a type="button" class="accordion-titulo btn btn-light btn-sm">Mas filtros<span
                        class="toggle-icon"></span></a>
                <div class="accordion-content">

                    <form id="formularioFiltro">
                        @csrf

                        <div class="row">

                            <!-- Filtro por Fecha -->
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="filtroFecha"
                                    placeholder="Filtrar por Fecha">
                            </div>

                            <!-- Otros filtros aquí... -->
                            <div class="col-md-3">
                                <select class="form-select js-example-basic-single " style="width: 100%"
                                    name="filtroTurno">
                                    <option value=" " selected disabled>Turno </option>
                                    <option value=" ">Sin Filtro Turno</option>
                                    @foreach ($turnos as $turno)
                                    <option value="{{ $turno->NomTurno }}">{{ $turno->NomTurno }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select class="form-select js-example-basic-single " style="width: 100%"
                                    name="filtroProv">
                                    <option selected disabled>Proveedor</option>
                                    <option value=" ">Sin Filtro Proveedor</option>
                                    @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->descripcion }}">{{ $proveedor->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select js-example-basic-single " style="width: 100%"
                                    name="filtroEmpresa">
                                    <option selected disabled>Filtro Empresa</option>
                                    <option value=" ">Sin Filtro Empresa</option>

                                    @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa->descripcion }}">{{ $empresa->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-select js-example-basic-single " style="width: 100%"
                                    name="filtroEspecie">
                                    <option selected disabled>Filtro Especie</option>
                                    <option value=" ">Sin Filtro Especie</option>

                                    @foreach ($especies as $especie)
                                    <option value="{{ $especie->descripcion }}">{{ $especie->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select js-example-basic-single " style="width: 100%"
                                    name="filtroSupervisor">
                                    <option selected disabled>Filtro Supervisor</option>
                                    <option value=" ">Sin Filtro Supervisor</option>

                                    @foreach ($supervisores as $supervisor)
                                    <option value="{{ $supervisor->cod_usuario }}">{{ $supervisor->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select js-example-basic-single " style="width: 100%"
                                    name="filtroPlanillero">
                                    <option selected disabled>Filtro Planillero</option>
                                    <option value=" ">Sin Filtro Planillero</option>

                                    @foreach ($planilleros as $planillero)
                                    <option value="{{ $planillero->cod_usuario }}">{{ $planillero->nombre }}
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
        <div id="tabla-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Lote</th>
                        <th>Fecha Turno</th>
                        <th>Turno</th>
                        <th>Proveedor</th>
                        <th>Empresa</th>
                        <th>Especie</th>
                        <th>Supervisor</th>
                        <th>Planillero</th>
                        <th>Guardado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($planillas as $planilla)
                    <tr class="table-row"
                        onclick="window.location='{{ url('/planilla/' . $planilla->cod_planilla) }}';">
                        <td>{{ $planilla->lote }}</td>
                        <td>{{ date('d/m/Y', strtotime($planilla->fec_turno)) }}</td>
                        <td>{{ $planilla->turno }}</td>
                        <td>{{ $planilla->proveedor }}</td>
                        <td>{{ $planilla->empresa }}</td>
                        <td>{{ $planilla->especie }}</td>
                        <td>{{ $planilla->supervisor_nombre }}</td>
                        <td>{{ $planilla->planillero_nombre }}</td>
                        <td>
                            @if ($planilla->guardado == 1)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green"
                                class="bi bi-floppy" viewBox="0 0 16 16">
                                <path d="M11 2H9v3h2z" />
                                <path
                                    d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red"
                                class="bi bi-floppy" viewBox="0 0 16 16">
                                <path d="M11 2H9v3h2z" />
                                <path
                                    d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                            </svg>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
                                    <select class="form-select modalSelect" style="width: 100%" name="empresa">
                                        <option selected disabled>Seleccione Empresa</option>
                                        <option value=" ">Sin Empresa</option>
                                        @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->cod_empresa }}">{{ $empresa->descripcion }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="proveedor">Proveedor</label>
                                    <select class="form-select modalSelect" style="width: 100%" name="proveedor">
                                        <option selected disabled>Seleccione Proveedor</option>
                                        <option value=" ">Sin Proveedor</option>
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
                                    <select class="form-select modalSelect" style="width: 100%" name="especie">
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
                                    <select class="form-select modalSelect" style="width: 100%" name="turno">
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
                                    <select class="form-select modalSelect" style="width: 100%" name="supervisor"
                                        @if(session('user')['cod_rol']==2) disabled @endif>
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
    <script>
        $(document).ready(function () {


            $('#formularioFiltro').submit(function (event) {
                event.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('filtrar.tabla') }}',
                    data: $(this).serialize(),
                    dataType: 'json', // Indicar que esperamos un JSON como respuesta
                    success: function (response) {
                        actualizarTabla(response.planillas);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error en la solicitud AJAX: ' + status + ' - ' + error);
                    }
                });
            });

            function actualizarTabla(planillas) {
                // Limpiar la tabla existente
                $('#tabla-container  tbody').empty();

                // Llenar la tabla con los nuevos datos
                planillas.forEach(function (planilla) {
                    var estadoIcono = planilla.guardado == 1 ? 'green' : 'red';
                    var icono = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="' + estadoIcono + '" class="bi bi-floppy" viewBox="0 0 16 16">' +
                        '<path d="M11 2H9v3h2z"/>' +
                        '<path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>' +
                        '</svg>';
                    var fila = '<tr class="table-row" onclick="window.location=\'' + '{{ url("/planilla/") }}/' + planilla.cod_planilla + '\';">' +
                        '<td>' + planilla.lote + '</td>' +
                        '<td>' + formatDate(planilla.fec_turno) + '</td>' +
                        '<td>' + planilla.turno + '</td>' +
                        '<td>' + planilla.proveedor + '</td>' +
                        '<td>' + planilla.empresa + '<td>' + planilla.especie + '</td>' +
                        '<td>' + planilla.supervisor_nombre + '</td>' +
                        '<td>' + planilla.planillero_nombre + '</td>' +
                        '<td>' + icono + '</td>' +
                        '</tr>';
                    $('#tabla-container tbody').append(fila);
                });
            }
            function formatDate(dateString) {
                // Puedes usar una librería como moment.js para formatear la fecha, o simplemente hacerlo manualmente si prefieres
                var date = new Date(dateString);
                var day = date.getDate() + 1;
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                return day + '/' + month + '/' + year;
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            var filtroLoteAnterior = '';
            $('#filtroLote').on('input', function () {
                var filtroLoteValue = $(this).val().trim();



                if (filtroLoteValue !== filtroLoteAnterior) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('filtrar_lotes_en_tiempo_real') }}',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'filtroLote': filtroLoteValue
                        },
                        dataType: 'json',
                        success: function (response) {
                            actualizarTabla(response.planillas);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error en la solicitud AJAX: ' + status + ' - ' + error);
                        }
                    });
                    filtroLoteAnterior = filtroLoteValue;
                }
            });

            function actualizarTabla(planillas) {
                $('#tabla-container tbody').empty();

                planillas.forEach(function (planilla) {
                    var estadoIcono = planilla.guardado == 1 ? 'green' : 'red';
                    var icono = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="' + estadoIcono + '" class="bi bi-floppy" viewBox="0 0 16 16">' +
                        '<path d="M11 2H9v3h2z"/>' +
                        '<path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>' +
                        '</svg>';
                    var fila = '<tr class="table-row" onclick="window.location=\'' + '{{ url("/planilla/") }}/' + planilla.cod_planilla + '\';">' +
                        '<td>' + planilla.lote + '</td>' +
                        '<td>' + formatDate(planilla.fec_turno) + '</td>' +
                        '<td>' + planilla.turno + '</td>' +
                        '<td>' + planilla.proveedor + '</td>' +
                        '<td>' + planilla.empresa + '<td>' + planilla.especie + '</td>' +
                        '<td>' + planilla.supervisor_nombre + '</td>' +
                        '<td>' + planilla.planillero_nombre + '</td>' +
                        '<td>' + icono + '</td>' +
                        '</tr>';
                    $('#tabla-container tbody').append(fila);
                });
            }
            function formatDate(dateString) {
                // Puedes usar una librería como moment.js para formatear la fecha, o simplemente hacerlo manualmente si prefieres
                var date = new Date(dateString);
                var day = date.getDate() + 1;
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                return day + '/' + month + '/' + year;
            }
        });
    </script>



</body>

</html>