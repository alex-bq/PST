<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Planilla PST</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <nav class="navbar sticky-top navbar-expand" style="background-color: #fdfdfd;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/inicio') }}">
                <img src="{{ url('http://192.168.1.122/img/logo.png') }}" alt="Logo" height="50">
            </a>

            <div class="collapse navbar-collapse justify-content" id="navbarNavAltMarkup">
                <div class="navbar-nav d-flex align-items-center">

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
    <br>

    <div class="container-fluid align-text">

        @if(session('mensaje'))
        <div class="alert alert-{{ session('mensaje')['tipo'] }}" role="alert">
            {{ session('mensaje')['texto'] }}
        </div>
        @endif

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card" id="columna1">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="true" href="#">Principal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Detalle</a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body">
                        <form id="form1" action="{{ url('/agregar-registro') }}" method="POST">
                            @csrf
                            <div class="row">
                                <h4>Lote : {{ $desc_planilla->lote }}</h4>
                                <div class="col-md-6">

                                    <br>
                                    <h6>Corte Inicial</h6>
                                    <select class="form-select form-select-sm" name="cInicial"
                                        aria-label="Selecciona un corte inicial">
                                        <option selected></option>
                                        @foreach ($cortes as $corte)
                                        <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}</option>
                                        @endforeach
                                    </select>

                                    <h6>Proceso</h6>
                                    <select class="form-select form-select-sm" name="proceso"
                                        aria-label="Selecciona un proceso">
                                        <option selected></option>
                                        @foreach ($procesos as $proceso)
                                        <option value="{{ $proceso->cod_sproceso }}">{{ $proceso->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <h6>Calibre</h6>
                                    <select class="form-select form-select-sm" name="calibre"
                                        aria-label="Selecciona un calibre">
                                        <option selected></option>
                                        @foreach ($calibres as $calibre)
                                        <option value="{{ $calibre->cod_calib }}">{{ $calibre->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <br />
                                    <h6>Piezas</h6>
                                    <input type="number" class="form-control form-control-sm" name="piezas"
                                        placeholder="123" />

                                </div>

                                <div class="col-md-6">

                                    <br />

                                    <h6>Corte Final</h6>
                                    <select class="form-select form-select-sm" name="cFinal"
                                        aria-label="Selecciona un corte final">
                                        <option selected></option>
                                        @foreach ($cortes as $corte)
                                        <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <h6>Destino</h6>
                                    <select class="form-select form-select-sm" name="destino"
                                        aria-label="Selecciona un destino">
                                        <option selected></option>
                                        <option value="1">Destino 1</option>
                                        <option value="2">Destino 2</option>
                                        <option value="3">Destino 3</option>
                                    </select>

                                    <h6>Calidad</h6>
                                    <select class="form-select form-select-sm" name="calidad"
                                        aria-label="Selecciona una calidad">
                                        <option selected></option>
                                        @foreach ($calidades as $calidad)
                                        <option value="{{ $calidad->cod_cald }}">{{ $calidad->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <br />

                                    <h6>Kilos</h6>
                                    <input type="number" class="form-control form-control-sm" name="kilos"
                                        placeholder="123" />
                                </div>
                            </div>


                            <br />
                            <input type="hidden" name="idPlanilla" value="{{ $idPlanilla }}">


                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        Agregar
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-warning btn-lg" onclick="limpiarFormulario()">
                                        Limpiar
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div id="formularioPlanilla">
                            <div class='row'>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div id="mensajeError" class="alert alert-danger" style="display: none;"></div>
                                        <p><strong>Lote</strong></p>

                                        <p>{{ $desc_planilla->lote }}</p>


                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <p for="codEspecie"><strong>Especie</strong></p>
                                        <p>{{ $desc_planilla->especie }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p for="proveedor"><strong>Proveedor</strong></p>
                                        <p>{{ $desc_planilla->proveedor }}</p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p for="codEmpresa"><strong>Empresa</strong></p>
                                        <p>{{ $desc_planilla->empresa }}</p>
                                    </div>
                                </div>


                            </div>
                            @if(session('user')['cod_rol'] == 1)

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p for="proveedor"><strong>Fecha de Turno</strong></p>
                                        <p>{{ $desc_planilla->fec_turno }}</p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p for="codEmpresa"><strong>Turno</strong></p>
                                        <p>{{ $desc_planilla->turno }}</p>
                                    </div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p for="proveedor"><strong>Supervisor</strong></p>
                                        <p>{{ $desc_planilla->supervisor_nombre }}</p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p for="codEmpresa"><strong>Planillero</strong></p>
                                        <p>{{ $desc_planilla->planillero_nombre }}</p>
                                    </div>
                                </div>
                            </div>

                            @elseif(session('user')['cod_rol'] == 2 || session('user')['cod_rol'] == 3)

                            <form action="{{ route('procesar.formulario') }}" method="post">
                                @csrf


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fechaTurno">Fecha de Turno</label>
                                            <input type="date" class="form-control" id="fechaTurno"
                                                value="{{ $desc_planilla->fec_turno }}" name="fechaTurno" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="codTurno">Turno</label>
                                            <select class="form-select" name="turno">

                                                @foreach ($turnos as $turno)
                                                <option value="{{ $turno->codTurno }}" @if($desc_planilla->turno ==
                                                    $turno->NomTurno) selected @endif>
                                                    {{ $turno->NomTurno }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="codSupervisor">Supervisor</label>
                                            <select class="form-select" name="supervisor"
                                                @if(session('user')['cod_rol']==2) disabled @endif>
                                                <option selected disabled>Seleccione un supervisor</option>
                                                @foreach ($supervisores as $supervisor)
                                                <option value="{{ $supervisor->cod_usuario }}" @if($desc_planilla->
                                                    supervisor_nombre ==
                                                    $supervisor->nombre) selected @endif>
                                                    {{ $supervisor->nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="codPlanillero">Planillero</label>
                                            <select class="form-select" name="planillero">
                                                <option selected disabled>Seleccione un planillero</option>
                                                @foreach ($planilleros as $planillero)
                                                <option value="{{ $planillero->cod_usuario }}" @if($desc_planilla->
                                                    planillero_nombre ==
                                                    $planillero->nombre) selected @endif>
                                                    {{ $planillero->nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <br><br>

                                <button type="submit" class="btn btn-primary">Modificar planilla</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h1>Planilla Control De Proceso SG</h1>
                        <div class="table-wrapper" id="tabla-registros">
                            <table class="table table-striped">
                                <thead class="sticky-header">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Corte Inicial</th>
                                        <th scope="col">Corte Final</th>
                                        <th scope="col">Proceso</th>
                                        <th scope="col">Destino</th>
                                        <th scope="col">Calibre</th>
                                        <th scope="col">Calidad</th>
                                        <th scope="col">Piezas</th>
                                        <th scope="col">Kilos</th>
                                        <th scope="col">Seleccionar</th>
                                        <th scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $contador = 1;
                                    @endphp
                                    @if(is_object($planilla))
                                    @foreach($planilla as $i)
                                    <tr>
                                        <th>{{$contador}}</th>
                                        <td>{{$i->cInicial}}</td>
                                        <td>{{$i->cFinal}}</td>
                                        <td>{{$i->proceso}}</td>
                                        <td>xx</td>
                                        <td>{{$i->calibre}}</td>
                                        <td>{{$i->calidad}}</td>
                                        <td>{{$i->piezas}}</td>
                                        <td>{{$i->kilos}}</td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckDefault" />
                                                <label class="form-check-label" for="flexCheckDefault">
                                                </label>
                                            </div>
                                        </td>
                                        <td><a href="">editar</a></td>
                                    </tr>
                                    @php
                                    $contador++;
                                    @endphp
                                    @endforeach
                                    @else
                                    <p>La planilla no existe o no tienes permisos para acceder.</p>
                                    @endif
                                </tbody>
                            </table>



                        </div>
                        <div class="row mt-4">

                            <div class="col-4">
                                <h6>Entrega Frigorífico</h6>
                                <label for="cajasEntrega">Cajas:</label>
                                <input type="number" class="form-control form-control-sm" id="cajasEntrega"
                                    placeholder="Cajas" />
                                <label for="kilosEntrega">Kilos:</label>
                                <input type="number" class="form-control form-control-sm" id="kilosEntrega"
                                    placeholder="Kilos" />
                            </div>
                            <div class="col-4">
                                <h6>Recepción Planta</h6>
                                <label for="cajasRecepcion">Cajas:</label>
                                <input type="number" class="form-control form-control-sm" id="cajasRecepcion"
                                    placeholder="Cajas" />
                                <label for="kilosRecepcion">Kilos:</label>
                                <input type="number" class="form-control form-control-sm" id="kilosRecepcion"
                                    placeholder="Kilos" />
                            </div>
                            <div class=col-4>
                                <button type="submit" class="btn btn-success btn-lg">
                                    Agregar
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $('form').submit(function (event) {
                    event.preventDefault();

                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {

                                actualizarTabla(response.planilla);

                            } else {
                                alert('Error al insertar el dato');
                            }
                        },
                        error: function () {
                            alert('Error al procesar la solicitud');
                        }
                    });
                });



                function actualizarTabla(planilla) {
                    var tabla = $('#tabla-registros table tbody');
                    tabla.empty();

                    $.each(planilla, function (index, registro) {
                        var nuevaFila = '<tr>' +
                            '<th scope="row">' + (index + 1) + '</th>' +
                            '<td>' + registro.cInicial + '</td>' +
                            '<td>' + registro.cFinal + '</td>' +
                            '<td>' + registro.proceso + '</td>' +
                            '<td>xx</td>' + // falta el destino 
                            '<td>' + registro.calibre + '</td>' +
                            '<td>' + registro.calidad + '</td>' +
                            '<td>' + registro.piezas + '</td>' +
                            '<td>' + registro.kilos + '</td>' +
                            '<td>' +
                            '<div class="form-check">' +
                            '<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />' +
                            '<label class="form-check-label" for="flexCheckDefault"></label>' +
                            '</div>' +
                            '</td>' +
                            '<td><a href="">editar</a></td>' +
                            '</tr>';

                        tabla.append(nuevaFila);
                    });
                }
            });
        </script>
        <script>
            $(document).ready(function () {
                // Oculta el formulario inicial
                $('#formularioPlanilla').hide();

                // Maneja el cambio de pestañas
                $('.nav-link').on('click', function () {
                    // Remueve la clase 'active' de todas las pestañas
                    $('.nav-link').removeClass('active');

                    // Agrega la clase 'active' a la pestaña seleccionada
                    $(this).addClass('active');

                    // Verifica la opción seleccionada
                    var opcionSeleccionada = $(this).text().trim();

                    // Muestra u oculta el formulario correspondiente
                    if (opcionSeleccionada === 'Principal') {
                        $('#formularioPlanilla').hide();
                        $('#form1').show();
                    } else if (opcionSeleccionada === 'Detalle') {
                        $('#form1').hide();
                        $('#formularioPlanilla').show();
                    }
                });
            });
        </script>

        <script>
            function limpiarFormulario() {
                document.getElementById('form1').reset();
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</body>

</html>