@extends('layouts.main-iframe')

@section('title', 'Página de inicio')

@section('styles')

    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('scripts')

    <script src="{{ asset('js/index.js') }}"></script>
@endsection

@section('content')
    <div class="container">


        <div class="mb-3">
            <div class="row d-flex justify-content-between align-items-center">
                <h1 class="mb-4">Planillas</h1>

                <form action="{{ route('planillas') }}" method="GET" id="formFiltros">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="date" class="form-control" name="fechaInicio"
                                    value="{{ request('fechaInicio') }}" placeholder="Fecha Inicio">
                                <span class="input-group-text">hasta</span>
                                <input type="date" class="form-control" name="fechaFin" value="{{ request('fechaFin') }}"
                                    placeholder="Fecha Fin">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary" type="submit">Buscar</button>

                        </div>

                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" data-bs-href="{{ url('/nueva-planilla') }}">Nueva
                                Planilla</button>
                        </div>
                    </div>

                    <div class="accordion-container mt-3">
                        <a type="button" class="accordion-titulo btn btn-light btn-sm">Mas filtros<span
                                class="toggle-icon"></span></a>
                        <div class="accordion-content">
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="filtroLote" name="filtroLote"
                                            placeholder="Filtrar por Lote" value="{{ request('filtroLote') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-select js-example-basic-single" style="width: 100%"
                                            name="filtroTurno">
                                            <option value="" selected disabled>Turno</option>
                                            <option value="" {{ request('filtroTurno') == '' ? 'selected' : '' }}>Sin Filtro
                                                Turno
                                            </option>
                                            @foreach ($turnos as $turno)
                                                <option value="{{ $turno->nombre }}" {{ request('filtroTurno') == $turno->nombre ? 'selected' : '' }}>
                                                    {{ $turno->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select class="form-select js-example-basic-single" style="width: 100%"
                                        name="filtroProv">
                                        <option selected disabled>Proveedor</option>
                                        <option value=" " selected disabled>Sin Filtro Proveedor</option>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{ $proveedor->descripcion }}" {{ request('filtroProv') == $proveedor->descripcion ? 'selected' : '' }}>
                                                {{ $proveedor->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select js-example-basic-single" style="width: 100%"
                                        name="filtroEmpresa">
                                        <option selected disabled>Filtro Empresa</option>
                                        <option value=" " selected disabled>Sin Filtro Empresa</option>

                                        @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->descripcion }}" {{ request('filtroEmpresa') == $empresa->descripcion ? 'selected' : '' }}>
                                                {{ $empresa->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="form-select js-example-basic-single" style="width: 100%"
                                        name="filtroEspecie">
                                        <option selected disabled>Filtro Especie</option>
                                        <option value=" " selected disabled>Sin Filtro Especie</option>

                                        @foreach ($especies as $especie)
                                            <option value="{{ $especie->descripcion }}" {{ request('filtroEspecie') == $especie->descripcion ? 'selected' : '' }}>
                                                {{ $especie->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select js-example-basic-single" style="width: 100%"
                                        name="filtroSupervisor">
                                        <option selected disabled>Filtro Supervisor</option>
                                        <option value=" " selected disabled>Sin Filtro Supervisor</option>

                                        @foreach ($supervisores as $supervisor)
                                            <option value="{{ $supervisor->cod_usuario }}" {{ request('filtroSupervisor') == $supervisor->cod_usuario ? 'selected' : '' }}>
                                                {{ $supervisor->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select js-example-basic-single" style="width: 100%"
                                        name="filtroPlanillero">
                                        <option selected disabled>Filtro Planillero</option>
                                        <option value=" " selected disabled>Sin Filtro Planillero</option>

                                        @foreach ($planilleros as $planillero)
                                            <option value="{{ $planillero->cod_usuario }}" {{ request('filtroPlanillero') == $planillero->cod_usuario ? 'selected' : '' }}>
                                                {{ $planillero->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <a href="{{ route('planillas') }}" class="btn btn-secondary">Limpiar</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>

                <div class="col-md-6 text-end mt-3">

                </div>
            </div>
        </div>


        @if(count($planillas) > 0)
            <div id="tabla-container">
                <table id="tablaPlanillas" class="table table-hover table-responsive" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th scope="col" onclick="sortTable(0)">N°</th>
                            <th scope="col" onclick="sortTable(1)">Lote</th>
                            <th scope="col" onclick="sortTable(2)">Fecha Turno</th>
                            <th scope="col" onclick="sortTable(3)">Turno</th>
                            <th scope="col" onclick="sortTable(4)">Proveedor</th>
                            <th scope="col" onclick="sortTable(5)">Empresa</th>
                            <th scope="col" onclick="sortTable(6)">Especie</th>
                            <th scope="col" onclick="sortTable(7)">Supervisor</th>
                            <th scope="col" onclick="sortTable(8)">Planillero</th>
                            @if(session('user')['cod_rol'] == 3)
                                <th></th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($planillas as $planilla)
                            <tr class="table-row" onclick="abrirModal('{{ $planilla->cod_planilla }}')">
                                <td>{{ $planilla->cod_planilla }}</td>
                                <td>{{ $planilla->lote }}</td>
                                <td>{{ date('Y-m-d', strtotime($planilla->fec_turno)) }}</td>
                                <td>{{ $planilla->turno }}</td>
                                <td>{{ $planilla->proveedor }}</td>
                                <td>{{ $planilla->empresa }}</td>
                                <td>{{ $planilla->especie }}</td>
                                <td>{{ $planilla->supervisor_nombre }}</td>
                                <td>{{ $planilla->planillero_nombre }}</td>
                                @if(session('user')['cod_rol'] == 3)
                                    <td>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="eliminarPlanilla('{{ $planilla->cod_planilla }}'); event.stopPropagation();">Eliminar</button>
                                    </td>
                                @endif


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No hay datos de planilla disponibles.</p>
        @endif
    </div>



@endsection


@section('modal')

    <div class="modal fade" id="verPlanillaModal" tabindex="-1" aria-labelledby="verPlanillaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-body">
                    <!-- Aquí se mostrará la información de la planilla -->
                    <iframe id="iframePlanilla" style="width:100%;height:700px;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_planilla" class="form-label">Tipo de Planilla <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" name="tipo_planilla" id="tipo_planilla" required>
                                    <option value="" selected disabled>Seleccione tipo de planilla</option>
                                    @foreach($tipos_planilla as $tipo)
                                        <option value="{{ $tipo->cod_tipo_planilla }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="codLote" class="form-label">Lote <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="codLote" name="codLote"
                                    placeholder="Ingrese el lote" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codEmpresa">Empresa</label>
                                    <select class="form-control" style="width: 100%" name="empresa" disabled>
                                        <option selected disabled></option>
                                        @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->cod_empresa }}">{{ $empresa->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="proveedor">Proveedor</label>
                                    <select class="form-control" name="proveedor" disabled>
                                        <option selected disabled></option>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{ $proveedor->cod_proveedor }}">{{ $proveedor->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codEspecie">Especie</label>
                                    <select class="form-control" name="especie" disabled>
                                        <option selected disabled></option>
                                        @foreach ($especies as $especie)
                                            <option value="{{ $especie->cod_especie }}">{{ $especie->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="proceso">Proceso</label>
                                    <select class="form-control" name="proceso" disabled>
                                        <option selected disabled></option>
                                        @foreach ($procesos as $proceso)
                                            <option value="{{ $proceso->cod_sproceso }}">{{ $proceso->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fechaTurno">Fecha de Turno</label>
                                    <input type="date" class="form-control" id="fechaTurno" name="fechaTurno"
                                        min="2000-01-01" max="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="horaInicio">Hora de Inicio</label>
                                    <input type="time" class="form-control" id="horaInicio" name="horaInicio" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="codTurno">Turno</label>
                                    <select class="form-select modalSelect" name="turno">
                                        <option selected disabled>Seleccione un turno</option>
                                        @foreach ($turnos as $turno)
                                            <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="codSupervisor">Supervisor</label>
                                    <select class="form-select modalSelect" name="supervisor"
                                        @if(session('user')['cod_rol'] == 2) disabled @endif>
                                        <option selected disabled>Seleccione un supervisor</option>
                                        @foreach ($supervisores as $supervisor)
                                            <option value="{{ $supervisor->cod_usuario }}" @if(
                                                session('user')['cod_rol'] == 2 &&
                                                session('user')['cod_usuario'] == $supervisor->cod_usuario
                                            ) selected @endif>
                                                {{ $supervisor->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="codPlanillero">Planillero</label>
                                    <select class="form-select modalSelect" name="planillero"
                                        @if(session('user')['cod_rol'] == 1) disabled @endif>
                                        <option selected disabled>Seleccione un planillero</option>
                                        @foreach ($planilleros as $planillero)
                                            <option value="{{ $planillero->cod_usuario }}" @if(
                                                session('user')['cod_rol'] == 1 &&
                                                session('user')['cod_usuario'] == $planillero->cod_usuario
                                            ) selected @endif>
                                                {{ $planillero->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="codJefeTurno">Jefe de Turno</label>
                                    <select class="form-select modalSelect" name="jefe_turno"
                                        @if(session('user')['cod_rol'] == 4) disabled @endif>
                                        <option selected disabled>Seleccione un jefe de turno</option>
                                        @foreach ($jefes_turno as $jefe)
                                            <option value="{{ $jefe->cod_usuario }}" @if(
                                                session('user')['cod_rol'] == 4 &&
                                                session('user')['cod_usuario'] == $jefe->cod_usuario
                                            ) selected @endif>
                                                {{ $jefe->nombre }}
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
@endsection




@section('scripts2')
    <script>
        function eliminarPlanilla(idPlanilla) {
            if (confirm("¿Estás seguro de que quieres eliminar esta planilla?")) {
                $.ajax({
                    url: "{{ url('/eliminar-planilla') }}/" + idPlanilla,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Error al eliminar la planilla");
                    }
                });
            }
        }

        function abrirModal(codPlanilla) {
            var url = "{{ url('/ver-planilla/') }}/" + codPlanilla;
            document.getElementById("iframePlanilla").src = url;
            $('#verPlanillaModal').modal('show');
        }

        $(function () {
            $(".accordion-titulo").click(function (e) {
                e.preventDefault();
                var contenido = $(this).next(".accordion-content");
                if (contenido.css("display") == "none") {
                    contenido.slideDown(250);
                    $(this).addClass("open");
                } else {
                    contenido.slideUp(250);
                    $(this).removeClass("open");
                }
            });
        });

        $('#codLote').on('blur', function () {
            var loteValue = $(this).val();
            if (loteValue.trim() !== '') {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('obtener_valores_lote') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'lote': loteValue
                    },
                    success: function (response) {
                        $('select[name="empresa"]').val(response.cod_empresa)
                        $('select[name="proveedor"]').val(response.cod_proveedor)
                        $('select[name="especie"]').val(response.cod_especie)
                        $('select[name="proceso"]').val(response.cod_sproceso)
                        $('#mensajeError').hide()
                    },
                    error: function (xhr) {
                        $('select[name="empresa"]').val('');
                        $('select[name="proveedor"]').val('');
                        $('select[name="especie"]').val('');
                        $('select[name="proceso"]').val('');
                        $('#mensajeError').text('El lote no existe.').show();
                    }
                });
            }
        });
        $(document).ready(function () {




            $('#formularioPlanilla').submit(function (event) {
                event.preventDefault();
                var errores = [];

                // Validaciones
                if (!$('select[name="tipo_planilla"]').val()) {
                    errores.push("Debe seleccionar un tipo de planilla");
                }
                if (!$('#codLote').val()) {
                    errores.push("Debe ingresar un lote");
                }
                if (!$('select[name="empresa"]').val()) {
                    errores.push("Debe seleccionar una empresa");
                }
                if (!$('select[name="proveedor"]').val()) {
                    errores.push("Debe seleccionar un proveedor");
                }
                if (!$('select[name="especie"]').val()) {
                    errores.push("Debe seleccionar una especie");
                }
                if (!$('select[name="proceso"]').val()) {
                    errores.push("Debe seleccionar un proceso");
                }
                if (!$('#fechaTurno').val()) {
                    errores.push("Debe seleccionar una fecha de turno");
                }
                if (!$('#horaInicio').val()) {
                    errores.push("Debe ingresar una hora de inicio");
                }
                if (!$('select[name="turno"]').val()) {
                    errores.push("Debe seleccionar un turno");
                }
                if (!$('select[name="supervisor"]').val()) {
                    errores.push("Debe seleccionar un supervisor");
                }
                if (!$('select[name="planillero"]').val()) {
                    errores.push("Debe seleccionar un planillero");
                }
                if (!$('select[name="jefe_turno"]').val()) {
                    errores.push("Debe seleccionar un jefe de turno");
                }

                if (errores.length > 0) {
                    let mensajeError = "<ul>";
                    errores.forEach(function (error) {
                        mensajeError += "<li>" + error + "</li>";
                    });
                    mensajeError += "</ul>";
                    toastr.error(mensajeError);
                    return false;
                }

                // Mostrar toast de carga
                $("#toast").show();
                $("#toast-text").text("Creando planilla...");

                var datos = {
                    tipo_planilla: $('select[name="tipo_planilla"]').val(),
                    codLote: $('#codLote').val(),
                    empresa: $('select[name="empresa"]').val(),
                    proveedor: $('select[name="proveedor"]').val(),
                    especie: $('select[name="especie"]').val(),
                    proceso: $('select[name="proceso"]').val(),
                    fechaTurno: $('#fechaTurno').val(),
                    horaInicio: $('#horaInicio').val(),
                    turno: $('select[name="turno"]').val(),
                    supervisor: $('select[name="supervisor"]').val(),
                    planillero: $('select[name="planillero"]').val(),
                    jefeTurno: $('select[name="jefe_turno"]').val()
                };

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/procesar-formulario',
                    data: datos,
                    success: function (response) {
                        $("#toast").hide();
                        sessionStorage.setItem("planillaCreada", "true");
                        window.location.href = baseUrl + '/planilla/' + response.planilla;
                    },
                    error: function (xhr, status, error) {
                        $("#toast").hide();
                        if (xhr.status === 419) {
                            console.error('Error CSRF');
                            toastr.error('Error de sesión. Por favor, recargue la página.');
                        } else {
                            console.error(xhr.responseText);
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error('Error al crear la planilla');
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection