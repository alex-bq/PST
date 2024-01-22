

$(document).ready(function () {
    
    $('.js-example-basic-single').select2({
        placeholder: 'Select an option',
        width: 'resolve'
      });
    // Código para el manejo del formulario
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

    // Código para actualizar la tabla
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

    $('#formularioPlanilla').hide();

    // Código para ocultar/mostrar formularios según la pestaña seleccionada
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

    // Función para limpiar el formulario
    
});
function limpiarFormulario () {
    document.getElementById('form1').reset();
    $('.js-example-basic-single').val(null).trigger('change');

}