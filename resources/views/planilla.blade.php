<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Planilla PST</title>

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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/planilla.js') }}"></script>
</head>

<body>
    <nav class="navbar sticky-top navbar-expand" style="background-color: #fdfdfd;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/inicio') }}">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" height="50">
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
            <div class="col-lg-4 mb-4">
                <div class="card" id="columna1">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="true">Principal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link">Detalle</a>
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
                                    <div class="form-group">
                                        <select class="form-select js-example-basic-single " style="width: 100%"
                                            name="cInicial" aria-label="Selecciona un corte inicial">
                                            <option disabled selected>Seleccione Corte Inicial</option>
                                            @foreach ($cortes as $corte)
                                            <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <h6>Proceso</h6>
                                    <div class="form-group">
                                        <select class="form-select js-example-basic-single" style="width: 100%"
                                            name="proceso" aria-label="Selecciona un proceso">
                                            <option disabled selected>Seleccione Proceso</option>
                                            @foreach ($procesos as $proceso)
                                            <option value="{{ $proceso->cod_sproceso }}">{{ $proceso->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <h6>Calibre</h6>
                                    <div class="form-group">
                                        <select class="form-select js-example-basic-single" style="width: 100%"
                                            name="calibre" aria-label="Selecciona un calibre">
                                            <option disabled selected>Seleccione Calibre</option>
                                            @foreach ($calibres as $calibre)
                                            <option value="{{ $calibre->cod_calib }}">{{ $calibre->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <br />
                                    <h6>Piezas</h6>
                                    <div class="form-group">
                                        <input type="number" class="form-control " name="piezas" placeholder="123" />
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <br />

                                    <h6>Corte Final</h6>
                                    <div class="form-group">
                                        <select class="form-select js-example-basic-single" style="width: 100%"
                                            name="cFinal" aria-label="Selecciona un corte final">
                                            <option disabled selected>Seleccione Corte Final</option>
                                            @foreach ($cortes as $corte)
                                            <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <h6>Destino</h6>
                                    <div class="form-group">
                                        <select class="form-select js-example-basic-single" style="width: 100%"
                                            name="destino" aria-label="Selecciona un destino">
                                            <option disabled selected>Seleccione Destino</option>
                                            <option value="1">Destino 1</option>
                                            <option value="2">Destino 2</option>
                                            <option value="3">Destino 3</option>
                                        </select>
                                    </div>

                                    <h6>Calidad</h6>
                                    <div class="form-group">
                                        <select class="form-select js-example-basic-single" style="width: 100%"
                                            name="calidad" aria-label="Selecciona una calidad">
                                            <option disabled selected>Seleccione Calidad</option>
                                            @foreach ($calidades as $calidad)
                                            <option value="{{ $calidad->cod_cald }}">{{ $calidad->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <br />

                                    <h6>Kilos</h6>
                                    <div class="form-group">
                                        <input type="number" class="form-control" name="kilos" placeholder="1.55"
                                            step="0.01" />
                                    </div>
                                </div>
                            </div>

                            <br />
                            <input type="hidden" name="idPlanilla" value="{{ $idPlanilla }}" />

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
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div id="mensajeError" class="alert alert-danger" style="display: none;"></div>
                                        <p><strong>Lote</strong></p>

                                        <p>{{ $desc_planilla->lote }}</p>


                                    </div>
                                </div>
                                <div class="col-lg-8">
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


            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h1>Planilla Control De Proceso SG</h1>
                        <div class="table-wrapper table-responsive" id="tabla-registros">
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
                                        <td>{{round($i->kilos,2)}}</td>
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
                                    Guardar </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

        <script>
            $(document).ready(function () {

                function limpiarFormulario() {
                    document.getElementById('form1').reset();
                }
            });
        </script>
</body>

</html>