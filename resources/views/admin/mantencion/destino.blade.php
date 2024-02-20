@extends('layouts.main-iframe')

@section('title', 'Mantencion Destino')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/mantencion.css') }}">
@endsection


@section('content')
<div class="container ">
    <h1>Destinos</h1>

    <div class="d-flex justify-content-end">
        <button id="btnNuevo" class="btn btn-success">Nuevo Destino</button>
    </div>
    <br>
    <div class="table">
        <table class="table table-striped table-custom tablaOrdenable">
            <thead class="sticky-header">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Activo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tablaDestinos">
                @foreach($destinos as $destino)
                <tr>
                    <td>{{ $destino->cod_destino }}</td>
                    <td>{{ $destino->nombre }}</td>
                    <td>
                        @if ($destino->activo == 1)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green"
                            class="bi bi-check-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                            <path
                                d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                        </svg>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-ban"
                            viewBox="0 0 16 16">
                            <path
                                d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                        </svg>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-end">

                            <button class="btn btn-light me-2"
                                onclick="modalEditarDestino({{ $destino->cod_destino }}, '{{ $destino->nombre }}', {{ $destino->activo }})">Editar</button>

                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection



@section('modal')
<div class="modal fade" id="modalDestino" tabindex="-1" aria-labelledby="modalDestinoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDestinoLabel">Nuevo Destino</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formDestino" action="" method="POST">
                    @csrf
                    <input type="hidden" id="cod_destino" name="cod_destino">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="activo" class="form-label">Activo:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="activo" id="activoSi" value="1" checked>
                            <label class="form-check-label" for="activoSi">
                                Si
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="activo" id="activoNo" value="0">
                            <label class="form-check-label" for="activoNo">
                                No
                            </label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection





@section('scripts2')
<script>
    $('#mantencion').addClass('show');
    $('#item-mantencion').addClass('selected');




    $("#btnNuevo").click(function () {
        $("#cod_destino").val("");
        $("#nombre").val("");
        $("#activo").val("");
        $("#modalDestinoLabel").text("Nuevo Destino");
        $("#modalDestino").modal("show");
    });

    function modalEditarDestino(idDestino, nombre, activo) {
        $('#cod_destino').val(idDestino);
        $('#nombre').val(nombre);
        if (activo == 1) {
            $('#activoSi').prop('checked', true);
        } else {
            $('#activoNo').prop('checked', true);
        }
        $('#modalDestinoLabel').text('Editar Destino');
        $('#modalDestino').modal("show");
    }


    $("#formDestino").submit(function (event) {
        event.preventDefault();

        var formData = $(this).serialize();

        var url = $('#modalDestinoLabel').text() === 'Nuevo Destino' ? '{{ route("guardarDestino") }}' : '{{ route("editarDestino") }}';


        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            success: function (response) {


                alert(response.message);

                if (response.error === 0) {
                    location.reload();
                }

            },
            error: function (xhr, status, error) {
                alert('Error al guardar el destino.');
                console.error(xhr.responseText);
            }
        });
    });


</script>

@endsection