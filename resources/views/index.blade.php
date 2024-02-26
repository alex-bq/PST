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
            <h1>Inicio</h1>
            <p id="saludo" class="text-body-secondary"></p>
            <hr><br><br>
            <div class="col-md-6">

                <!-- <input type="text" class="form-control" id="filtroLote" name="filtroLote"
                    placeholder="Filtrar por Lote"> -->
            </div>

            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal"
                    data-bs-href="{{ url('/nueva-planilla') }}">Nueva Planilla</button>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <h4>Sin Guardar</h4>
        <div id="tabla-container">
            <table id="tablaNoGuardado" class="table table-hover table-responsive" style="font-size: 13px;">
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($noGuardado as $planilla)
                    <tr class="table-row"
                        onclick="window.location='{{ url('/planilla/' . $planilla->cod_planilla) }}';">
                        <td>{{ $planilla->cod_planilla }}</td>
                        <td>{{ $planilla->lote }}</td>
                        <td>{{ date('Y-m-d', strtotime($planilla->fec_turno)) }}</td>
                        <td>{{ $planilla->turno }}</td>
                        <td>{{ $planilla->proveedor }}</td>
                        <td>{{ $planilla->empresa }}</td>
                        <td>{{ $planilla->especie }}</td>
                        <td>{{ $planilla->supervisor_nombre }}</td>
                        <td>{{ $planilla->planillero_nombre }}</td>
                        <td>
                            <button class="btn btn-danger"
                                onclick="eliminarPlanilla('{{ $planilla->cod_planilla }}'); event.stopPropagation();">Eliminar</button>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>


        </div>

    </div>


    <div class="mb-3">
        <h4>Hoy</h4>
        <div id="tabla-container">
            <table id="tablaPlanillasHoy" class="table table-hover table-responsive" style="font-size: 13px;">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($planillasHoy as $planilla)
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

                    </tr>
                    @endforeach
                </tbody>
            </table>


        </div>

    </div>


    @if(count($planillas7dias) > 0)
    <div id="tabla-container">
        <h4>Últimos 7 días</h4>
        <table id="tablaPlanillas7dias" class="table table-hover table-responsive" style="font-size: 13px;">
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
                </tr>
            </thead>
            <tbody>
                @foreach($planillas7dias as $planilla)
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
                <iframe id="iframePlanilla" style="width:100%;height:500px;" frameborder="0"></iframe>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechaTurno">Fecha de Turno</label>
                                <input type="date" class="form-control" id="fechaTurno" name="fechaTurno"
                                    min="2000-01-01" max="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codTurno">Turno</label>
                                <select class="form-select modalSelect" name="turno">
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
                                <select class="form-select modalSelect" name="supervisor"
                                    @if(session('user')['cod_rol']==2) disabled @endif>
                                    <option selected disabled>Seleccione un supervisor</option>
                                    @foreach ($supervisores as $supervisor)
                                    <option value="{{ $supervisor->cod_usuario }}" @if(session('user')['cod_rol']==2 &&
                                        session('user')['cod_usuario']==$supervisor->cod_usuario) selected
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
                                <select class="form-select modalSelect" name="planillero"
                                    @if(session('user')['cod_rol']==1) disabled @endif>
                                    <option selected disabled>Seleccione un planillero</option>
                                    @foreach ($planilleros as $planillero)
                                    <option value="{{ $planillero->cod_usuario }}" @if(session('user')['cod_rol']==1 &&
                                        session('user')['cod_usuario']==$planillero->cod_usuario) selected
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

    var hora = new Date().getHours();

    var saludo = "";
    if (hora >= 6 && hora < 12) {
        saludo = "Buenos días";
    } else if (hora >= 12 && hora < 18) {
        saludo = "Buenas tardes";
    } else {
        saludo = "Buenas noches";
    }
    var saludoElemento = document.getElementById("saludo");
    saludoElemento.textContent = saludo + " {{ session('user')['nombre'] }}";

    $(document).ready(function () {




        $('#formularioPlanilla').submit(function (event) {
            event.preventDefault();
            var codLote = $('#codLote').val();
            var empresa = $('select[name="empresa"]').val();
            var proveedor = $('select[name="proveedor"]').val();
            var especie = $('select[name="especie"]').val();
            var proceso = $('select[name="proceso"]').val();
            var fechaTurno = $('#fechaTurno').val();
            var turno = $('select[name="turno"]').val();
            var supervisor = $('select[name="supervisor"]').val();
            var planillero = $('select[name="planillero"]').val();

            var datos = {
                codLote: codLote,
                empresa: empresa,
                proveedor: proveedor,
                especie: especie,
                proceso: proceso,
                fechaTurno: fechaTurno,
                turno: turno,
                supervisor: supervisor,
                planillero: planillero
            };

            $.ajax({
                type: 'POST',
                url: baseUrl + '/procesar-formulario',
                data: datos,
                success: function (response) {
                    sessionStorage.setItem("planillaCreada", "true");
                    window.location.href = baseUrl + '/planilla/' + response.planilla;
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 419) {
                        console.error('Error CSRF');
                    } else {
                        console.error(xhr.responseText);
                        $('#mensajeError').text('Error en la creación').show();
                    }
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        var planillaSaved = sessionStorage.getItem("planillaSaved");

        if (planillaSaved === "true") {
            toastr.success("Planilla Guardada correctamente");
            sessionStorage.removeItem("planillaSaved");
        }
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
    });
</script>

@endsection