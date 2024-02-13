@extends('layouts.plantilla')

@section('title', 'Planilla PST')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('scripts')

<script src="{{ asset('js/planilla.js') }}"></script>

@endsection


@section('content')

<div class="container-fluid align-text">
    <div class='row' style="margin-bottom: -30px;margin-top: -1px;">
        <div class="col-md-4">
            <a href="{{ url('/inicio') }}" class="botonAtras">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                    style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                    <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                </svg>
            </a>
        </div>
        <div class="col-md-8 ">
            <h3>Planilla Control De Proceso SG</h3>
        </div>
    </div>

    @if(session('mensaje'))
    <div class="alert alert-{{ session('mensaje')['tipo'] }}" role="alert">
        {{ session('mensaje')['texto'] }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-2">
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
                                        <!-- <option value="nuevo">Nuevo corte</option> -->

                                        @foreach ($cortes as $corte)
                                        <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}</option>
                                        @endforeach
                                    </select>
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
                                    <select id="cFinal" class="form-select select2" style="width: 100%" name="cFinal"
                                        aria-label="Selecciona un corte final">
                                        <option></option>
                                        <!-- <option value="nuevo">Nuevo corte</option> -->

                                        @foreach ($cortes as $corte)
                                        <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
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
                                            <!-- <option value="nuevo">Nuevo calibre</option> -->
                                            @foreach ($calibres as $calibre)
                                            <option value="{{ $calibre->cod_calib }}">{{ $calibre->nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

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
                                    <select id="calidad" class="form-select select2" style="width: 100%" name="calidad"
                                        aria-label="Selecciona una calidad">
                                        <option></option>
                                        <!-- <option value="nuevo">Nuevo calidad</option> -->
                                        @foreach ($calidades as $calidad)
                                        <option value="{{ $calidad->cod_cald }}">{{ $calidad->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
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
                                    <select id="destino" class="form-select select2" style="width: 100%" name="destino"
                                        aria-label="Selecciona un destino">
                                        <option></option>
                                        <!-- <option value="nuevo">Nuevo destino</option> -->
                                        @foreach ($destinos as $destino)
                                        <option value="{{ $destino->cod_destino }}">{{ $destino->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Piezas</h6>
                                <div class="form-group">
                                    <input type="number" class="form-control " name="piezas" placeholder="123" />
                                </div>
                            </div>
                            <div class="col-md-6">
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
                                <button type="submit" class="btn btn-success btn-lg ">
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
                            <h5><strong>Lote </strong>
                                <p>{{ $desc_planilla->lote }}</p>
                            </h5>
                            <p for="codEspecie"><strong>Especie : </strong>{{ $desc_planilla->especie }}</p>
                            <p for="codProceso"><strong>Proceso : </strong>{{ $desc_planilla->proceso }}</p>
                            <p for="proveedor"><strong>Proveedor : </strong>{{ $desc_planilla->proveedor }}</p>
                            <p for="codEmpresa"><strong>Empresa : </strong>{{ $desc_planilla->empresa }}</p>
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
                                            @php
                                            $selected = ($desc_planilla->cod_supervisor ==
                                            $supervisor->cod_usuario) ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $supervisor->cod_usuario }}" {{ $selected }}>
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
                                            @php
                                            $selected = ($desc_planilla->cod_planillero ==
                                            $planillero->cod_usuario) ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $planillero->cod_usuario }}" {{ $selected }}>
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
                        <div class="row" style="margin-top: -20px;">
                            <div class="col-6">
                                <h6>Entrega Frigorífico</h6>
                                <label for="cajasEntrega">Cajas:</label>
                                <input type="number" class="form-control form-control-sm" id="cajasEntrega"
                                    value="{{ $detalle_planilla->cajas_entrega }}" name="cajas_entrega"
                                    placeholder="Cajas">
                                <label for="kilosEntrega">Kilos:</label>
                                <input type="number" class="form-control form-control-sm" id="kilosEntrega"
                                    value="{{ round($detalle_planilla->kilos_entrega,2) }}" name="kilos_entrega"
                                    placeholder="Kilos">
                                <label for="piezasEntrega">Piezas:</label>
                                <input type="number" class="form-control form-control-sm" id="piezasEntrega"
                                    value="{{ $detalle_planilla->piezas_entrega }}" name="piezas_entrega"
                                    placeholder="Piezas">
                            </div>
                            <div class="col-6">
                                <h6>Recepción Planta</h6>
                                <label for="cajasRecepcion">Cajas:</label>
                                <input type="number" class="form-control form-control-sm" id="cajasRecepcion"
                                    value="{{ $detalle_planilla->cajas_recepcion }}" name="cajas_recepcion"
                                    placeholder="Cajas">
                                <label for="kilosRecepcion">Kilos:</label>
                                <input type="number" class="form-control form-control-sm" id="kilosRecepcion"
                                    value="{{ round($detalle_planilla->kilos_recepcion,2) }}" name="kilos_recepcion"
                                    placeholder="Kilos">
                                <label for="piezasRecepcion">Piezas:</label>
                                <input type="number" class="form-control form-control-sm" id="piezasRecepcion"
                                    value="{{ $detalle_planilla->piezas_recepcion }}" name="piezas_recepcion"
                                    placeholder="Piezas">
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-6">
                                <h6>Sala </h6>

                                <select id="sala" class="form-select select2" style="width: 100%" name="sala"
                                    aria-label="Selecciona una sala">
                                    <option></option>
                                    @foreach ($salas as $sala)
                                    @php
                                    $selected = ($detalle_planilla && $sala->cod_sala ==
                                    $detalle_planilla->cod_sala) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $sala->cod_sala }}" {{ $selected }}>{{ $sala->nombre }}
                                    </option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-6">
                                <h6>Dotación:</h6>
                                <input type="number" class="form-control" name="dotacion" id="dotacion"
                                    value="{{ $detalle_planilla->dotacion }}">
                            </div>
                        </div>

                        <br>
                        <div class="form-group mb-4">
                            <h6>Observación:</h6>
                            <textarea class="form-control" name="observacion" id="observacion"
                                rows="3">{{ $detalle_planilla->observacion }}</textarea>

                        </div>
                        <input type="hidden" name="idPlanilla" value="{{ $idPlanilla }}" />

                        <button type="submit" class="btn btn-primary" id='btnGuardarPlanilla'>Guardar
                            Planilla</button>
                    </form>
                </div>
            </div>

        </div>


        <div class="col-lg-8 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 text-end">

                        </div>
                    </div>

                    <div class="table-wrapper table-responsive" id="tabla-registros">

                        <table class="table table-striped">
                            <thead class="sticky-header">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Corte Inicial</th>
                                    <th scope="col">Corte Final</th>
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
                                    <td>{{$i->destino}}</td>
                                    <td>{{$i->calibre}}</td>
                                    <td>{{$i->calidad}}</td>
                                    <td>{{$i->piezas}}</td>
                                    <td>{{round($i->kilos,2)}}</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                data-id="{{ $i->cod_reg }}" />
                                            <label class="form-check-label" for="flexCheckDefault"></label>
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

                        </div>
                    </div>
                    <div class="row ">


                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-success" id="btnGuardar">
                                Guardar </button>
                        </div>


                        <div class="col-sm-8 text-end">
                            <button id="btnBorrarSeleccionados" class="btn btn-danger "
                                data-planilla-id="{{ $idPlanilla }}">Borrar
                                Seleccionados</button>
                            <a href="{{ route('verPlanilla', ['id' => $idPlanilla]) }}" class="btn btn-dark">
                                Ver Planilla
                            </a>



                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

@endsection


@section('modal')


<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <!-- Aquí colocarás tu formulario de edición -->
                <form id="formEditarReg" action="{{ url('/modificar-registro') }}" method="POST">
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <h6>Corte Inicial</h6>

                            <div id="input-corteIniEditar" class="form-group">
                                <select id="cInicialEditar" class="form-select select2Modal " style="width: 100%"
                                    name="cInicialEditar" aria-label="Selecciona un corte inicial">


                                    @foreach ($cortes as $corte)
                                    <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>



                            <h6>Calibre</h6>



                            <div id="input-calibreEditar" class="form-group">
                                <div class="input-group">
                                    <select id="calibreEditar" class="form-select select2Modal" style="width: 65%"
                                        name="calibreEditar" aria-label="Selecciona un calibre">

                                        @foreach ($calibres as $calibre)
                                        <option value="{{ $calibre->cod_calib }}">{{ $calibre->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                            <h6>Sala </h6>
                            <div class="form-group">
                                <select id="salaEditar" class="form-select select2Modal" style="width: 100%"
                                    name="salaEditar" aria-label="Selecciona un sala">

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
                                <select id="cFinalEditar" class="form-select select2Modal" style="width: 100%"
                                    name="cFinalEditar" aria-label="Selecciona un corte final">


                                    @foreach ($cortes as $corte)
                                    <option value="{{ $corte->cod_corte }}">{{ $corte->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <h6>Calidad</h6>

                            <div id="input-calidadEditar" class="form-group">
                                <select id="calidadEditar" class="form-select select2Modal" style="width: 100%"
                                    name="calidadEditar" aria-label="Selecciona una calidad">

                                    @foreach ($calidades as $calidad)
                                    <option value="{{ $calidad->cod_cald }}">{{ $calidad->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <h6>Destino</h6>

                            <div id="input-destinoEditar" class="form-group">
                                <select id="destinoEditar" class="form-select select2Modal" style="width: 100%"
                                    name="destinoEditar" aria-label="Selecciona un destino">

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

@endsection





@section('scripts2')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<script>
    function descargarURLComoPDF(url) {
        // URL de la página que deseas convertir a PDF

        // Realizar una solicitud GET para obtener el contenido HTML de la URL
        fetch(url)
            .then(response => response.text())
            .then(html => {
                // Crear un objeto jsPDF
                var doc = new jsPDF();

                // Convertir el contenido HTML a PDF
                doc.html(html, {
                    callback: function (pdf) {
                        // Guardar el PDF con un nombre específico
                        pdf.save('contenido.pdf');
                    }
                });
            })
            .catch(error => {
                console.error('Error al obtener el contenido HTML:', error);
            });
    }
</script>

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
@endsection
</body>

</html>