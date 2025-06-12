@extends('layouts.main-iframe')

@section('title', 'Planilla PST')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/planilla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cargandoToast.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            color: #212529;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }

        .back-button:hover {
            background-color: #000120;
            color: #f8f9fa;
            text-decoration: none;
        }

        .back-button svg {
            width: 16px;
            height: 16px;
        }
    </style>
@endsection

@section('scripts')

    <script src="{{ asset('js/planilla.js') }}"></script>
    <script src="{{ asset('js/js-plantilla/cargandoToast.js') }}"></script>

@endsection


@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="vY" id="toast" style="display: none;">
        <div class="vX">
            <div class="vh">
                <div class="vZ">
                    <div class="loader">⠋</div>
                    <span id="toast-text">Cargando...</span>
                </div>
            </div>
        </div>
    </div>

    <div id="planillaData" data-hora-inicio="{{ $desc_planilla->hora_inicio }}"
        data-hora-termino="{{ $desc_planilla->hora_termino }}" data-id-planilla="{{ $desc_planilla->cod_planilla }}">
    </div>

    <div class="container-fluid align-text">
        @if(session('mensaje'))
            <div class="alert alert-{{ session('mensaje')['tipo'] }}" role="alert">
                {{ session('mensaje')['texto'] }}
            </div>
        @endif
        <div class="text-end" style="margin-top: 5px;margin-bottom: 5px;">
            <div class="row align-items-center">
                <div class="col-auto">
                    <a href="javascript:history.back()" class="back-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                        </svg>
                        Volver
                    </a>
                </div>
                <div class="col">
                    <div class="row align-items-center justify-content-end">
                        <div class="col-auto">

                            <a href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                    class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
                                    <path
                                        d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header border-bottom py-3">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">
                    <i class="bi bi-graph-up"></i> Dashboard de Producción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <!-- Indicadores en cards -->
                <div class="px-3 pt-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">
                                                <i class="bi bi-clock me-2"></i>Tiempo Trabajado
                                            </h6>
                                            <h4 class="mb-0" id="tiempoTrabajado">0h 0m</h4>
                                        </div>
                                        <div class="fs-1 text-info">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">
                                                <i class="bi bi-speedometer2 me-2"></i>Productividad
                                            </h6>
                                            <h4 class="mb-0" id="productividad">0 kg/persona/hora</h4>
                                        </div>
                                        <div class="fs-1 text-primary">
                                            <i class="bi bi-graph-up-arrow"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">
                                                <i class="bi bi-percent me-2"></i>Rendimiento
                                            </h6>
                                            <h4 class="mb-0" id="rendimiento">0.0%</h4>
                                        </div>
                                        <div class="fs-1 text-success">
                                            <i class="bi bi-graph-up-arrow"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($desc_planilla->cod_tipo_planilla != 2)
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">
                                                    <i class="bi bi-percent me-2"></i>Premium
                                                </h6>
                                                <h4 class="mb-0" id="porcentajePremium">0.0%</h4>
                                                <small class="text-muted" id="kilosPremium">0.00 kg</small>
                                            </div>
                                            <div class="fs-1 text-warning">
                                                <i class="bi bi-star-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tabla con título -->
                <div class="mt-4">
                    <div class="px-3 pb-2 border-bottom">
                        <h6 class="mb-0">
                            <i class="bi bi-table me-2"></i>Detalle por Corte y Calidad
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table id='totales' class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small text-nowrap px-3">Corte Final</th>
                                    <th class="small px-3">Calidad</th>
                                    <th class="small text-end px-3">Piezas</th>
                                    <th class="small text-end px-3">Kilos</th>
                                    <th class="small text-end px-3">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subtotal as $x)
                                    <tr>
                                        <td class="px-3">{{$x->corte_final}}</td>
                                        <td class="px-3">{{$x->calidad}}</td>
                                        <td class="text-end px-3">{{number_format($x->total_piezas, 0, '.', ',')}}</td>
                                        <td class="text-end px-3">{{number_format($x->total_kilos, 2, '.', ',')}}</td>
                                        <td class="text-end px-3">{{number_format($x->porcentaje_del_total, 2, '.', ',')}}%</td>
                                    </tr>
                                @endforeach
                                @foreach($total as $a)
                                    <tr id="filaTotal" class="table-secondary fw-bold">
                                        <th class="px-3">{{$a->corte_final}}</th>
                                        <th class="px-3">{{$a->calidad}}</th>
                                        <td class="text-end px-3" id="totalPiezas">
                                            {{number_format($a->total_piezas, 0, '.', ',')}}
                                        </td>
                                        <td class="text-end px-3" id="totalKilos">
                                            {{number_format($a->total_kilos, 2, '.', ',')}}
                                        </td>
                                        <td class="text-end px-3" id="totalPorcentaje">
                                            {{number_format($a->porcentaje_del_total, 2, '.', ',')}}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-4 mb-2" style="padding-right: 0.25rem;">

                <div class="card" id="columna1">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active tabPlanilla" href aria-current="true">Registro</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link tabPlanilla" href id="detalleTab">Editar</a>
                            </li>

                            <li class="col text-end">
                                @if($desc_planilla->guardado == 1)
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
                                        <input type="number" min="0" class="form-control " name="piezas"
                                            placeholder="123" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Kilos</h6>
                                    <div class="form-group">
                                        <input type="number" min="0" class="form-control" name="kilos" placeholder="1.55"
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
                                <p><strong>N°</strong>{{ $desc_planilla->cod_planilla }}</p>
                                <p><strong>Lote </strong>{{ $desc_planilla->lote }}</p>

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

                            @elseif(session('user')['cod_rol'] == 2 || session('user')['cod_rol'] == 3 || session('user')['cod_rol'] == 4)

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
                                                                                                            <option value="{{ $turno->id }}" @if(
                                                                                                                $desc_planilla->turno == $turno->nombre
                                                                                                            ) selected @endif>
                                                                                                                {{ $turno->nombre }}
                                                                                                            </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-bottom: 1rem;">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="codSupervisor">Supervisor</label>
                                                                <select class="form-select select2" style="width: 100%" name="supervisor"
                                                                    @if(session('user')['cod_rol'] == 2) disabled @endif>
                                                                    @foreach ($supervisores as $supervisor)
                                                                                                            @php
                                                                                                                $selected = ($desc_planilla->cod_supervisor == $supervisor->cod_usuario) ? 'selected' : '';
                                                                                                            @endphp
                                                                                                            <option value="{{ $supervisor->cod_usuario }}" {{ $selected }}>
                                                                                                                {{ $supervisor->nombre }}
                                                                                                            </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="codPlanillero">Planillero</label>
                                                                <select class="form-select select2" style="width: 100%" name="planillero"
                                                                    @if(session('user')['cod_rol'] == 1) disabled @endif>
                                                                    @foreach ($planilleros as $planillero)
                                                                                                            @php
                                                                                                                $selected = ($desc_planilla->cod_planillero == $planillero->cod_usuario) ? 'selected' : '';
                                                                                                            @endphp
                                                                                                            <option value="{{ $planillero->cod_usuario }}" {{ $selected }}>
                                                                                                                {{ $planillero->nombre }}
                                                                                                            </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="codJefeTurno">Jefe de Turno</label>
                                                                <select class="form-select select2" style="width: 100%" name="jefe_turno"
                                                                    @if(session('user')['cod_rol'] == 4) disabled @endif>
                                                                    @foreach ($jefes_turno as $jefe)
                                                                                                            @php
                                                                                                                $selected = ($desc_planilla->cod_jefe_turno == $jefe->cod_usuario) ? 'selected' : '';
                                                                                                            @endphp
                                                                                                            <option value="{{ $jefe->cod_usuario }}" {{ $selected }}>
                                                                                                                {{ $jefe->nombre }}
                                                                                                            </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary" id="btnModificar" disabled>Modificar
                                                        planilla</button>
                                                </form>
                            @endif
                        </div>



                    </div>
                </div>

            </div>


            <div class="col-lg-8 mb-2" style="margin-bottom: 0px !important;padding-left: 0.25rem;">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 text-end">

                            </div>
                        </div>

                        <div class="table-wrapper table-responsive w-100" id="tabla-registros">

                            <table class="table table-striped w-100">
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
                                        <th scope="col">Producto Objetivo</th>
                                        <th scope="col">Seleccionar</th>
                                        <th scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 13px;">
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
                                                                                                <td>{{round($i->kilos, 2)}}</td>
                                                                                                <td>
                                                                                                    <div class="form-check">
                                                                                                        <input class="form-check-input producto-objetivo" type="checkbox"
                                                                                                            data-id="{{ $i->cod_reg }}" {{ $i->es_producto_objetivo ? 'checked' : '' }}>
                                                                                                    </div>
                                                                                                </td>
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


                        <div class="row ">


                            <div class="col-sm-4">
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#modalTiemposMuertos">
                                    Tiempos Muertos
                                </button>

                            </div>


                            <div class="col-sm-8 text-end">
                                <button class="btn btn-primary btn-sm me-2" type="button" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                    <i class="bi bi-bar-chart-fill me-1"></i> Ver Totales
                                </button>
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

            <div class="row mt-4" id="seccionEntrega">
                <div class="col-12" style="padding-right: 0px;">
                    <div class="card" style="margin-top: 0px;">
                        <div class="card-header">
                            <h5 class="mb-0">Entrega de Planilla</h5>
                        </div>
                        <div class="card-body">
                            <form id='formEntrega' action="{{ route('guardar') }}" method="post"
                                style="margin-bottom: 0px;">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <h6>Tipo de Planilla: {{ $desc_planilla->tipo_planilla_nombre }}</h6>
                                            <input type="hidden" id="tipo_planilla"
                                                value="{{ $desc_planilla->cod_tipo_planilla }}">
                                        </div>
                                        <div class="row">
                                            <!-- Entrega Frigorífico -->
                                            <div class="col-4">
                                                <h6>Entrega M.P</h6>
                                                <div id="entrega_piezas" class="mb-2"
                                                    style="display: {{ $desc_planilla->cod_tipo_planilla == 2 || $desc_planilla->cod_tipo_planilla == 4 ? 'none' : 'block' }}">
                                                    <label for="piezasEntrega">Piezas:</label>
                                                    <input type="number" min="0" class="form-control form-control-sm"
                                                        id="piezasEntrega"
                                                        value="{{ round($detalle_planilla->piezas_entrega) }}"
                                                        name="piezas_entrega" placeholder="Piezas">
                                                </div>
                                                <label for="kilosEntrega">Kilos:</label>
                                                <input type="number" min="0" class="form-control form-control-sm"
                                                    id="kilosEntrega"
                                                    value="{{ round($detalle_planilla->kilos_entrega, 2) }}"
                                                    name="kilos_entrega" placeholder="Kilos">
                                            </div>

                                            <!-- Recepción Planta -->
                                            <div class="col-4">
                                                <h6>Recepción Planta</h6>
                                                <div id="recepcion_piezas" class="mb-2"
                                                    style="display: {{ $desc_planilla->cod_tipo_planilla == 2 || $desc_planilla->cod_tipo_planilla == 4 ? 'none' : 'block' }}">
                                                    <label for="piezasRecepcion">Piezas:</label>
                                                    <input type="number" min="0" class="form-control form-control-sm"
                                                        id="piezasRecepcion"
                                                        value="{{ round($detalle_planilla->piezas_recepcion) }}"
                                                        name="piezas_recepcion" placeholder="Piezas">
                                                </div>
                                                <label for="kilosRecepcion">Kilos:</label>
                                                <input type="number" min="0" class="form-control form-control-sm"
                                                    id="kilosRecepcion"
                                                    value="{{ round($detalle_planilla->kilos_recepcion, 2) }}"
                                                    name="kilos_recepcion" placeholder="Kilos">
                                            </div>

                                            <!-- Producto Terminado -->
                                            <div class="col-4" id="producto_terminado"
                                                style="display: {{ $desc_planilla->cod_tipo_planilla == 2 || $desc_planilla->cod_tipo_planilla == 4 ? 'block' : 'none' }}">
                                                <h6>Producto Terminado</h6>
                                                <div class="mb-2">
                                                    <label for="embolsadoTerminado">Embolsado:</label>
                                                    <input type="number" min="0" class="form-control form-control-sm"
                                                        id="embolsadoTerminado" name="embolsado_terminado"
                                                        value="{{ isset($detalle_planilla->embolsado_terminado) ? $detalle_planilla->embolsado_terminado : '' }}"
                                                        placeholder="Embolsado">
                                                </div>
                                                <label for="kilosTerminado">Kilos:</label>
                                                <input type="number" min="0" class="form-control form-control-sm"
                                                    id="kilosTerminado" name="kilos_terminado"
                                                    value="{{ isset($detalle_planilla->kilos_terminado) ? round($detalle_planilla->kilos_terminado, 2) : '' }}"
                                                    placeholder="Kilos" step="0.01">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Segunda columna: Sala, Dotación y Observación -->
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="hora_termino">Hora de Término</label>
                                                <input type="time" class="form-control" id="hora_termino"
                                                    name="hora_termino"
                                                    value="{{ isset($desc_planilla->hora_termino) ? \Carbon\Carbon::parse($desc_planilla->hora_termino)->format('H:i') : '' }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <h6>Sala</h6>
                                                <select id="sala" class="form-select select2" name="sala" required>
                                                    <option value="" selected disabled hidden>Selecciona una sala</option>
                                                    @foreach ($salas as $sala)
                                                                                                    @php
                                                                                                        $selected = ($detalle_planilla && $sala->cod_sala == $detalle_planilla->cod_sala) ? 'selected' : '';
                                                                                                    @endphp
                                                                                                    <option value="{{ $sala->cod_sala }}" {{ $selected }}>
                                                                                                        {{ $sala->nombre }}
                                                                                                    </option>
                                                    @endforeach
                                                </select>
                                                <h6>Dotación</h6>
                                                <input type="number" min="1" class="form-control" name="dotacion"
                                                    id="dotacion" value="{{ $detalle_planilla->dotacion }}"
                                                    placeholder="Dotacion" required>
                                            </div>
                                            <div class="col">
                                                <h6>Observación</h6>
                                                <textarea class="form-control" name="observacion" id="observacion"
                                                    style="height: 108px;">{{ $detalle_planilla->observacion }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-success" id="btnGuardarPlanilla">Guardar
                                            Planilla</button>
                                    </div>
                                </div>

                                <input type="hidden" name="idPlanilla" value="{{ $idPlanilla }}" />
                            </form>
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
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
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

                            </div>
                            <div class="col-md-6">
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

                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
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
                            </div>

                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Piezas</h6>
                                <div class="form-group">
                                    <input type="number" min="0" class="form-control " id="piezasEditar" name="piezasEditar"
                                        placeholder="123" />
                                </div>

                            </div>
                            <div class="col-md-6">
                                <h6>Kilos</h6>
                                <div class="form-group">
                                    <input type="number" min="0" class="form-control" id="kilosEditar" name="kilosEditar"
                                        placeholder="1.55" step="0.01" />
                                </div>
                            </div>
                        </div>









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

    <div class="modal fade" id="modalTiemposMuertos" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 90vw;">
            <div class="modal-content" style="min-height: 80vh;">
                <div class="modal-header">
                    <h5 class="modal-title">Registro de Tiempos Muertos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario -->
                    <div class="mb-4">
                        <form id="formTiemposMuertos">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="departamento" class="form-label">Departamento a Cargo</label>
                                        <select class="form-control" id="departamento" name="cod_departamento" required>
                                            <option value="">Seleccione departamento</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="causa" class="form-label">Causa del Paro</label>
                                        <textarea class="form-control" id="causa" name="causa" rows="4" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <label for="horaInicio" class="form-label">Hora de Inicio</label>
                                    <input type="time" class="form-control form-control-lg" id="horaInicio"
                                        name="hora_inicio" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="horaTermino" class="form-label">Hora de Término</label>
                                    <input type="time" class="form-control form-control-lg" id="horaTermino"
                                        name="hora_termino_muerto" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success btn-lg w-100">Agregar</button>
                                </div>
                            </div>

                            <input type="hidden" name="idPlanilla" value="{{ $idPlanilla }}" />
                        </form>
                    </div>

                    <!-- Separador -->
                    <hr class="my-4">

                    <!-- Tabla de Tiempos Muertos -->
                    <div>
                        <h6 class="mb-3 fs-5">Tiempos Muertos Registrados</h6>
                        <div id="listaTiemposMuertos" style="max-height: 400px; overflow-y: auto;">
                            <!-- La tabla se generará dinámicamente aquí -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection





@section('scripts2')

    <script>
        $(document).ready(function () {
            var planillaCreada = sessionStorage.getItem("planillaCreada");

            if (planillaCreada === "true") {
                toastr.success("Planilla creada correctamente");
                sessionStorage.removeItem("planillaCreada");
            }

            // Manejar cambios en producto objetivo
            $('.producto-objetivo').change(function () {
                var codReg = $(this).data('id');
                var esObjetivo = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: '{{ route("actualizar.producto.objetivo") }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        cod_reg: codReg,
                        es_producto_objetivo: esObjetivo
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success('Estado actualizado correctamente');
                        } else {
                            toastr.error('Error al actualizar el estado');
                            // Revertir el cambio en caso de error
                            $(this).prop('checked', !esObjetivo);
                        }
                    },
                    error: function (xhr) {
                        toastr.error('Error al actualizar el estado');
                        // Revertir el cambio en caso de error
                        $(this).prop('checked', !esObjetivo);
                    }
                });
            });
        });
    </script>



    <script>
        document.querySelectorAll(".tabPlanilla").forEach(function (enlace) {
            enlace.addEventListener("click", function (event) {
                event.preventDefault();
            });
        });
        document.getElementById('reloadButton').addEventListener('click', function () {
            // Recarga la página
            location.reload();
        });
    </script>


@endsection
</body>

</html>