$(document).ready(function () {
    $(".js-example-basic-single").select2({
        placeholder: "Select an option",
        width: "resolve",
        theme: "bootstrap4",
    });

    $("#formPrincipal").submit(function (event) {
        var cInicial = $('select[name="cInicial"]').val();
        var proceso = $('select[name="proceso"]').val();
        var calibre = $('select[name="calibre"]').val();
        var piezas = $('input[name="piezas"]').val();
        var cFinal = $('select[name="cFinal"]').val();
        var destino = $('select[name="destino"]').val();
        var calidad = $('select[name="calidad"]').val();
        var kilos = $('input[name="kilos"]').val();

        var newCorte = $('input[name="newCorte"]').val();
        var newCalibre = $('input[name="newCalibre"]').val();
        var newDestino = $('input[name="newDestino"]').val();
        var newCalidad = $('input[name="newCalidad"]').val();

        // var corteVisible = $("#input-container-destino").is(":visible");
        // var calibreVisible = $("#input-container-calibre").is(":visible");
        // var destinoVisible = $("#input-container-destino").is(":visible");
        // var calidadVisible = $("#input-container-calidad").is(":visible");

        if (
            !cInicial ||
            !proceso ||
            !calibre ||
            !piezas ||
            !cFinal ||
            !destino ||
            !calidad ||
            !kilos ||
            (destino === "nuevo" && !newDestino) ||
            ((cInicial === "nuevo" || cFinal === "nuevo") && !newCorte) ||
            (calibre === "nuevo" && !newCalibre) ||
            (calidad === "nuevo" && !newCalidad)
        ) {
            alert(
                "Por favor, completa todos los campos antes de enviar el formulario."
            );
            event.preventDefault();
        } else {
            event.preventDefault();

            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        if (
                            newDestino ||
                            newCorte ||
                            newCalibre ||
                            newCalidad
                        ) {
                            limpiarFormulario();
                        }

                        actualizarTabla(response.planilla);
                    } else {
                        alert("Error al insertar el dato: " + response.mensaje);
                    }
                },
                error: function () {
                    alert("Error al procesar la solicitud");
                },
            });
        }
    });

    function actualizarTabla(planilla) {
        var tabla = $("#tabla-registros table tbody");
        tabla.empty();

        $.each(planilla, function (index, registro) {
            var nuevaFila =
                "<tr>" +
                '<th scope="row">' +
                (index + 1) +
                "</th>" +
                "<td>" +
                registro.cInicial +
                "</td>" +
                "<td>" +
                registro.cFinal +
                "</td>" +
                "<td>" +
                registro.proceso +
                "</td>" +
                "<td>xx</td>" +
                "<td>" +
                registro.calibre +
                "</td>" +
                "<td>" +
                registro.calidad +
                "</td>" +
                "<td>" +
                registro.piezas +
                "</td>" +
                "<td>" +
                parseFloat(registro.kilos).toFixed(2) +
                "</td>" +
                "<td>" +
                '<div class="form-check">' +
                '<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />' +
                '<label class="form-check-label" for="flexCheckDefault"></label>' +
                "</div>" +
                "</td>" +
                '<td><a href="">editar</a></td>' +
                "</tr>";

            tabla.append(nuevaFila);
        });
    }

    $("#formularioDetalle").hide();
    $("#formEntrega").hide();

    $(".nav-link").on("click", function () {
        $(".nav-link").removeClass("active");
        $(this).addClass("active");

        var opcionSeleccionada = $(this).text().trim();

        if (opcionSeleccionada === "Registro") {
            $("#formularioDetalle").hide();
            $("#formEntrega").hide();
            $("#formPrincipal").show();
        } else if (opcionSeleccionada === "Editar") {
            $("#formPrincipal").hide();
            $("#formEntrega").hide();
            $("#formularioDetalle").show();
        } else if (opcionSeleccionada === "Detalle") {
            $("#formPrincipal").hide();
            $("#formularioDetalle").hide();
            $("#formEntrega").show();
        }
    });
    $("#btnGuardar").on("click", function () {
        // Simplemente redirecciona a la opción "Detalle" al hacer clic en el botón "Guardar"
        $(".nav-link").removeClass("active");
        $("#formPrincipal").hide();
        $("#formularioDetalle").hide();
        $("#formEntrega").show();
        // Aquí podrías agregar una redirección específica si es necesario
        // window.location.href = "ruta_a_detalle.html";
    });

    var urlParams = new URLSearchParams(window.location.search);
    var tabParam = urlParams.get("tab");

    // Seleccionar la pestaña según el parámetro 'tab'
    if (tabParam === "detalle") {
        $(".nav-link").removeClass("active");
        $("#detalleTab").addClass("active"); // Asegúrate de que el enlace de la pestaña de detalle tenga el ID 'detalleTab'

        $("#formPrincipal").hide();
        $("#formularioDetalle").show();
    }
});

function limpiarFormulario() {
    document.getElementById("formPrincipal").reset();
    $(".js-example-basic-single").val(null).trigger("change");
}

document.addEventListener("DOMContentLoaded", function () {
    var btnModificar = document.getElementById("btnModificar");
    var fechaTurno = document.getElementById("fechaTurno");
    var turnoSelect = document.querySelector('select[name="turno"]');
    var supervisorSelect = document.querySelector('select[name="supervisor"]');
    var planilleroSelect = document.querySelector('select[name="planillero"]');

    // Guardar los valores iniciales
    var initialFechaTurno = fechaTurno.value;
    var initialTurno = turnoSelect.value;
    var initialSupervisor = supervisorSelect.value;
    var initialPlanillero = planilleroSelect.value;

    // Función para comprobar si hay cambios
    function checkChanges() {
        var cambios =
            fechaTurno.value !== initialFechaTurno ||
            turnoSelect.value !== initialTurno ||
            supervisorSelect.value !== initialSupervisor ||
            planilleroSelect.value !== initialPlanillero;

        // Habilitar o deshabilitar el botón según si hay cambios
        btnModificar.disabled = !cambios;
    }

    // Función para obtener los campos modificados
    function getModifiedFields() {
        var modifiedFields = {};

        if (fechaTurno.value !== initialFechaTurno) {
            modifiedFields.fechaTurno = fechaTurno.value;
        }

        if (turnoSelect.value !== initialTurno) {
            modifiedFields.turno = turnoSelect.value;
        }

        if (supervisorSelect.value !== initialSupervisor) {
            modifiedFields.supervisor = supervisorSelect.value;
        }

        if (planilleroSelect.value !== initialPlanillero) {
            modifiedFields.planillero = planilleroSelect.value;
        }

        return modifiedFields;
    }

    // Agregar event listeners para los cambios en los campos
    fechaTurno.addEventListener("change", function () {
        checkChanges();
    });

    // Usar el evento change.select2 para Select2
    $(turnoSelect).on("change.select2", function () {
        checkChanges();
    });

    $(supervisorSelect).on("change.select2", function () {
        checkChanges();
    });

    $(planilleroSelect).on("change.select2", function () {
        checkChanges();
    });

    // Manejar el envío del formulario
    $("#form2").submit(function (event) {
        event.preventDefault();

        var modifiedFields = getModifiedFields();
        modifiedFields._token = $('meta[name="csrf-token"]').attr("content");

        // Verificar si hay campos modificados
        if (Object.keys(modifiedFields).length > 0) {
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: modifiedFields,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        alert("CORRECTO!!!!!");
                        var currentUrl = window.location.href;
                        var newUrl;

                        if (currentUrl.includes("?")) {
                            newUrl = currentUrl + "&tab=detalle";
                        } else {
                            newUrl = currentUrl + "?tab=detalle";
                        }

                        window.location.href = newUrl;
                    } else {
                        alert("Error al insertar el dato");
                    }
                },
                error: function () {
                    alert("Error al procesar la solicitud");
                },
            });
        } else {
            alert("No se han realizado cambios en el formulario.");
        }
    });
});
