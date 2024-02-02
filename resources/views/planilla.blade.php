<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Planilla PST</title>

    <!-- Incluye Bootstrap CSS y Select2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">


    <!-- Incluye jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Incluye Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Tu archivo de estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/planilla.js') }}"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        var baseUrl = "{{ url('/') }}";
    </script>

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
    <div class="container-fluid">
        <div class='row'>
            <div class="col-lg-4 ">
                <a href="{{ url('/inicio') }}" class="botonAtras">
                    <img src="{{ asset('image/atras.svg') }}" alt="atras" style="height: 35px;">
                </a>
            </div>
            <div class="col-lg-8 ">
                <h1>Planilla Control De Proceso SG</h1>
            </div>
        </div>
    </div>

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
                                <a class="nav-link active" aria-current="true">Registro</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="detalleTab">Editar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="entregaTab">Detalle</a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body">

                        <form id="formPrincipal" action="{{ url('/agregar-registro') }}" method="POST">
                            @csrf
                            <div class="row">

                                <div class="col-md-6">
                                    <h6>Corte Inicial</h6>
                                    <div id="input-nuevo-corteIni" style="display: none;">
                                        <div class="input-group position-relative d-inline-flex align-items-center">
                                            <input placeholder="Nuevo valor" class="form-control " id="newCorteIni"
                                                name="newCorteIni" formControlName="textInput" type="text" value="">
                                            <i class="bi bi-x-lg position-absolute"
                                                style="right: 10px; cursor: pointer; z-index: 100; top: 50%; transform: translateY(-50%);"
                                                onclick='$("#cInicial").val(null).trigger("change"); $("#newCorteIni").val("");'>

                                            </i>
                                        </div>
                                    </div>
                                    <div id="input-corteIni" class="form-group">
                                        <select id="cInicial" class="form-select select2 " style="width: 100%"
                                            name="cInicial" aria-label="Selecciona un corte inicial">
                                            <option></option>
                                            <option value="nuevo">Nuevo corte</option>

                                            @foreach ($cortes as $corte)
                                            <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <h6>Calibre</h6>
                                    <div id="input-nuevo-calibre" style="display: none;">
                                        <div class="input-group position-relative d-inline-flex align-items-center">
                                            <input placeholder="Nuevo valor" class="form-control " id="newCalibre"
                                                name="newCalibre" formControlName="textInput" type="text" value="">
                                            <i class="bi bi-x-lg position-absolute"
                                                style="right: 10px; cursor: pointer; z-index: 100; top: 50%; transform: translateY(-50%);"
                                                onclick='$("#calibre").val(null).trigger("change"); $("#newCalibre").val("");'>

                                            </i>
                                        </div>
                                    </div>


                                    <div id="input-calibre" class="form-group">
                                        <div class="input-group">
                                            <select id="calibre" class="form-select select2" style="width: 65%"
                                                name="calibre" aria-label="Selecciona un calibre">
                                                <option></option>
                                                <option value="nuevo">Nuevo calibre</option>
                                                @foreach ($calibres as $calibre)
                                                <option value="{{ $calibre->cod_calib }}">{{ $calibre->nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>







                                    <h6>Sala </h6>
                                    <div class="form-group">
                                        <select id="sala" class="form-select select2" style="width: 100%" name="sala"
                                            aria-label="Selecciona un sala">
                                            <option></option>
                                            @foreach ($salas as $sala)
                                            <option value="{{ $sala->cod_sala }}">{{ $sala->nombre }}</option>
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
                                    <h6>Corte Final</h6>
                                    <div id="input-nuevo-corteFin" style="display: none;">
                                        <div class="input-group position-relative d-inline-flex align-items-center">
                                            <input placeholder="Nuevo valor" class="form-control " id="newCorteFin"
                                                name="newCorteFin" formControlName="textInput" type="text" value="">
                                            <i class="bi bi-x-lg position-absolute"
                                                style="right: 10px; cursor: pointer; z-index: 100; top: 50%; transform: translateY(-50%);"
                                                onclick='$("#cFinal").val(null).trigger("change"); $("#newCorteFin").val("");'>

                                            </i>
                                        </div>
                                    </div>
                                    <div id="input-corteFin" class="form-group">
                                        <select id="cFinal" class="form-select select2" style="width: 100%"
                                            name="cFinal" aria-label="Selecciona un corte final">
                                            <option></option>
                                            <option value="nuevo">Nuevo corte</option>

                                            @foreach ($cortes as $corte)
                                            <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <h6>Calidad</h6>
                                    <div id="input-nuevo-calidad" style="display: none;">
                                        <div class="input-group position-relative d-inline-flex align-items-center">
                                            <input placeholder="Nuevo valor" class="form-control " id="newCalidad"
                                                name="newCalidad" formControlName="textInput" type="text" value="">
                                            <i class="bi bi-x-lg position-absolute"
                                                style="right: 10px; cursor: pointer; z-index: 100; top: 50%; transform: translateY(-50%);"
                                                onclick='$("#calidad").val(null).trigger("change"); $("#newCalidad").val("");'>

                                            </i>
                                        </div>
                                    </div>
                                    <div id="input-calidad" class="form-group">
                                        <select id="calidad" class="form-select select2" style="width: 100%"
                                            name="calidad" aria-label="Selecciona una calidad">
                                            <option></option>
                                            <option value="nuevo">Nuevo calidad</option>
                                            @foreach ($calidades as $calidad)
                                            <option value="{{ $calidad->cod_cald }}">{{ $calidad->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <h6>Destino</h6>
                                    <div id="input-nuevo-destino" style="display: none;">
                                        <div class="input-group position-relative d-inline-flex align-items-center">
                                            <input placeholder="Nuevo valor" class="form-control " id="newDestino"
                                                name="newDestino" formControlName="textInput" type="text" value="">
                                            <i class="bi bi-x-lg position-absolute"
                                                style="right: 10px; cursor: pointer; z-index: 100; top: 50%; transform: translateY(-50%);"
                                                onclick='$("#destino").val(null).trigger("change"); $("#newDestino").val("");'>

                                            </i>
                                        </div>
                                    </div>
                                    <div id="input-destino" class="form-group">
                                        <select id="destino" class="form-select select2" style="width: 100%"
                                            name="destino" aria-label="Selecciona un destino">
                                            <option></option>
                                            <option value="nuevo">Nuevo destino</option>
                                            @foreach ($destinos as $destino)
                                            <option value="{{ $destino->cod_destino }}">{{ $destino->nombre }}</option>
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






                            <br>
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





                        <div id="formularioDetalle">
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

                            <form action="{{ route('modificar-planilla', ['id' => $idPlanilla]) }}" id="form2"
                                method="post">
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
                                            <select class="form-select select2" style="width: 100%" name="turno">

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
                                            <select class="form-select select2" style="width: 100%" name="supervisor"
                                                @if(session('user')['cod_rol']==2) disabled @endif>

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
                                            <select class="form-select select2" style="width: 100%" name="planillero">

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

                                <button type="submit" class="btn btn-primary" id="btnModificar" disabled>Modificar
                                    planilla</button>
                            </form>
                            @endif
                        </div>


                        <form id='formEntrega' action="{{ route('guardar') }}" method="post" class="mt-3">
                            @csrf
                            <div class="row">
                                <figcaption class="blockquote-footer">
                                    Campos Opcionales
                                </figcaption>

                                <div class="col-6">
                                    <h6>Entrega Frigorífico</h6>
                                    <label for="cajasEntrega">Cajas:</label>
                                    <input type="number" class="form-control form-control-sm" id="cajasEntrega"
                                        name="cajas_entrega" placeholder="Cajas">
                                    <label for="kilosEntrega">Kilos:</label>
                                    <input type="number" class="form-control form-control-sm" id="kilosEntrega"
                                        name="kilos_entrega" placeholder="Kilos">
                                    <label for="piezasEntrega">Piezas:</label>
                                    <input type="number" class="form-control form-control-sm" id="piezasEntrega"
                                        name="piezas_entrega" placeholder="Piezas">
                                </div>
                                <div class="col-6">
                                    <h6>Recepción Planta</h6>
                                    <label for="cajasRecepcion">Cajas:</label>
                                    <input type="number" class="form-control form-control-sm" id="cajasRecepcion"
                                        name="cajas_recepcion" placeholder="Cajas">
                                    <label for="kilosRecepcion">Kilos:</label>
                                    <input type="number" class="form-control form-control-sm" id="kilosRecepcion"
                                        name="kilos_recepcion" placeholder="Kilos">
                                    <label for="piezasRecepcion">Piezas:</label>
                                    <input type="number" class="form-control form-control-sm" id="piezasRecepcion"
                                        name="piezas_recepcion" placeholder="Piezas">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="dotacion">Dotación:</label>
                                <input type="number" class="form-control" name="dotacion" id="dotacion">
                            </div>

                            <div class="form-group mb-4">
                                <label for="observacion">Observación:</label>
                                <textarea class="form-control" name="observacion" id="observacion" rows="4"></textarea>
                            </div>
                            <input type="hidden" name="idPlanilla" value="{{ $idPlanilla }}" />

                            <button type="submit" class="btn btn-primary" id='btnGuardarPlanilla'>Guardar
                                Planilla</button>
                        </form>






                    </div>
                </div>
            </div>


            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        <!-- <h1>Planilla Control De Proceso SG</h1> -->
                        <div class="table-wrapper table-responsive" id="tabla-registros">
                            <table class="table table-striped">
                                <thead class="sticky-header">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Corte Inicial</th>
                                        <th scope="col">Corte Final</th>
                                        <th scope="col">Sala</th>
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
                                        <td>{{$i->sala}}</td>
                                        <td>{{$i->destino}}</td>
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
                                        <td>
                                            <a href="#" class="btn btn-primary btn-editar"
                                                data-id="{{ $i->cod_reg }}">Editar</a>

                                        </td>
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

                        <div class="container mt-4">
                            <div class="col-md-3">


                                <table id='totales' class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="small">

                                            </th>
                                            <th class="small">Piezas</th>
                                            <th class="small">Kilos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subtotal as $x)
                                        <tr>
                                            <td>{{$x->cFinal}}</td>
                                            <td>{{$x->subtotalPiezas}}</td>
                                            <td>{{round($x->subtotalKilos,2)}}</td>
                                        </tr>


                                        @endforeach
                                        @foreach($total as $a)
                                        <tr id="filaTotal">
                                            <th>Total</th>
                                            <td>{{$a->totalPiezas}}</td>
                                            <td>{{round($a->totalKilos,2)}}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>


                                <div class="row mt-4">


                                    <div class="col-4">
                                        <button type="submit" class="btn btn-success btn-lg" id="btnGuardar">
                                            Guardar </button>
                                    </div>

                                </div>




                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Registro</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>

                            </div>
                            <div class="modal-body">
                                <!-- Aquí colocarás tu formulario de edición -->
                                <form id="formEditarReg" action="{{ url('/modificar-registro') }}" method="POST">
                                    @csrf
                                    <div class="row">

                                        <div class="col-md-6">
                                            <h6>Corte Inicial</h6>

                                            <div id="input-corteIniEditar" class="form-group">
                                                <select id="cInicialEditar" class="form-select select2Modal "
                                                    style="width: 100%" name="cInicialEditar"
                                                    aria-label="Selecciona un corte inicial">


                                                    @foreach ($cortes as $corte)
                                                    <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>



                                            <h6>Calibre</h6>



                                            <div id="input-calibreEditar" class="form-group">
                                                <div class="input-group">
                                                    <select id="calibreEditar" class="form-select select2Modal"
                                                        style="width: 65%" name="calibreEditar"
                                                        aria-label="Selecciona un calibre">

                                                        @foreach ($calibres as $calibre)
                                                        <option value="{{ $calibre->cod_calib }}">{{ $calibre->nombre }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                            </div>
                                            <h6>Sala </h6>
                                            <div class="form-group">
                                                <select id="salaEditar" class="form-select select2Modal"
                                                    style="width: 100%" name="salaEditar"
                                                    aria-label="Selecciona un sala">

                                                    @foreach ($salas as $sala)
                                                    <option value="{{ $sala->cod_sala }}">{{ $sala->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <br />
                                            <h6>Piezas</h6>
                                            <div class="form-group">
                                                <input type="number" class="form-control " id="piezasEditar" name="piezasEditar"
                                                    placeholder="123" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h6>Corte Final</h6>

                                            <div id="input-corteFinEditar" class="form-group">
                                                <select id="cFinalEditar" class="form-select select2Modal"
                                                    style="width: 100%" name="cFinalEditar"
                                                    aria-label="Selecciona un corte final">


                                                    @foreach ($cortes as $corte)
                                                    <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <h6>Calidad</h6>

                                            <div id="input-calidadEditar" class="form-group">
                                                <select id="calidadEditar" class="form-select select2Modal"
                                                    style="width: 100%" name="calidadEditar"
                                                    aria-label="Selecciona una calidad">

                                                    @foreach ($calidades as $calidad)
                                                    <option value="{{ $calidad->cod_cald }}">{{ $calidad->nombre }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <h6>Destino</h6>

                                            <div id="input-destinoEditar" class="form-group">
                                                <select id="destinoEditar" class="form-select select2Modal"
                                                    style="width: 100%" name="destinoEditar"
                                                    aria-label="Selecciona un destino">

                                                    @foreach ($destinos as $destino)
                                                    <option value="{{ $destino->cod_destino }}">{{ $destino->nombre }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>



                                            <br />

                                            <h6>Kilos</h6>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="kilosEditar" name="kilosEditar"
                                                    placeholder="1.55" step="0.01" />
                                            </div>
                                        </div>
                                    </div>

                                    <br />
                                    <input type="hidden" name="idRegistro" id="idRegistro" />
                                    <input type="hidden" name="idPlanilla" value="{{ $idPlanilla }}" />
                                    
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary ">
                                                Guardar Cambios
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>



                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
                    crossorigin="anonymous"></script>

                <script>
                    $(document).ready(function () {
                        $('#cInicial').on('change', function () {
                            var cInicialEsnuevo = $('#cInicial').val() === 'nuevo';


                            // Mostrar u ocultar el contenedor según las condiciones
                            if (cInicialEsnuevo) {

                                $('#input-corteIni').fadeOut(0);
                                $('#input-nuevo-corteIni').fadeIn(300);
                            } else {
                                $('#input-nuevo-corteIni').fadeOut(0);
                                $('#input-corteIni').fadeIn(300);
                            }

                            console.log('Selección cambiada para cInicial ');
                        });
                        $('#cFinal').on('change', function () {
                            var cFinalEsnuevo = $('#cFinal').val() === 'nuevo';

                            // Mostrar u ocultar el contenedor según las condiciones
                            if (cFinalEsnuevo) {
                                $('#input-corteFin').fadeOut(0);
                                $('#input-nuevo-corteFin').fadeIn(300);

                            } else {
                                $('#input-nuevo-corteFin').fadeOut(0);
                                $('#input-corteFin').fadeIn(300);
                            }

                            console.log('Selección cambiada para cfinal');
                        });

                        $('#calibre').on('change', function () {
                            var calibreEsnuevo = $(this).val() === 'nuevo';

                            // Mostrar u ocultar el contenedor según las condiciones
                            if (calibreEsnuevo) {
                                $('#input-calibre').fadeOut(0);
                                $('#input-nuevo-calibre').fadeIn(300);

                            } else {
                                $('#input-nuevo-calibre').fadeOut(0);
                                $('#input-calibre').fadeIn(300);
                            }

                            console.log('Selección cambiada para calibre');
                        });

                        // Repite el mismo patrón para otros campos como calidad, destino, etc.

                        $('#calidad').on('change', function () {
                            var calidadEsnuevo = $(this).val() === 'nuevo';

                            // Mostrar u ocultar el contenedor según las condiciones
                            if (calidadEsnuevo) {
                                $('#input-calidad').fadeOut(0);
                                $('#input-nuevo-calidad').fadeIn(300);

                            } else {
                                $('#input-nuevo-calidad').fadeOut(0);
                                $('#input-calidad').fadeIn(300);
                            }

                            console.log('Selección cambiada para calidad');
                        });

                        $('#destino').on('change', function () {
                            var destinoEsnuevo = $(this).val() === 'nuevo';

                            if (destinoEsnuevo) {
                                $('#input-destino').fadeOut(0);
                                $('#input-nuevo-destino').fadeIn(300);

                            } else {
                                $('#input-nuevo-destino').fadeOut(0);
                                $('#input-destino').fadeIn(300);
                            }

                            console.log('Selección cambiada para destino');
                        });
                    });
                </script>
</body>

</html>