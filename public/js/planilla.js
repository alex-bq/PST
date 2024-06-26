$(document).on("click", ".btn-editar", function (e) {
    e.preventDefault();

    var filaId = $(this).data("id");

    // Aquí puedes realizar la solicitud AJAX para obtener datos de la fila
    $.ajax({
        type: "GET",
        url: baseUrl + "/obtener-datos-fila/" + filaId,
        dataType: "json",
        success: function (response) {
            llenarFormularioEdicion(response);

            $("#modalEditar").modal("show");
        },
        error: function () {
            // Manejar errores si es necesario
        },
    });
    function llenarFormularioEdicion(response) {
        // Llenar el formulario de edición con los datos obtenidos
        // Aquí, debes seleccionar cada campo del formulario y asignarle el valor correspondiente desde 'datos'
        // Ejemplo:
        $("#idRegistro").val(response.cod_reg);
        $("#cInicialEditar").val(response.cod_corte_ini).trigger("change");
        $("#cFinalEditar").val(response.cod_corte_fin).trigger("change");

        // $("#salaEditar").val(response.cod_sala).trigger("change");

        $("#destinoEditar").val(response.cod_destino).trigger("change");
        $("#calibreEditar").val(response.cod_calibre).trigger("change");
        $("#calidadEditar").val(response.cod_calidad).trigger("change");
        $("#piezasEditar").val(response.piezas);
        $("#kilosEditar").val(response.kilos);
    }
});
$(document).ready(function () {
    $(".select2").select2({
        width: "resolve",
        theme: "bootstrap4",
    });
    $(".select2Modal").select2({
        width: "resolve",
        theme: "bootstrap4",
        dropdownParent: $("#modalEditar"),
    });
    $("#cInicial").select2({
        placeholder: "Seleccione Corte",
        width: "resolve",
        theme: "bootstrap4",
    });
    $("#cFinal").select2({
        placeholder: "Seleccione Corte",
        width: "resolve",
        theme: "bootstrap4",
    });
    $("#calibre").select2({
        placeholder: "Seleccione Calibre",
        width: "resolve",
        theme: "bootstrap4",
    });
    $("#calidad").select2({
        placeholder: "Seleccione Calidad",
        width: "resolve",
        theme: "bootstrap4",
    });
    $("#sala").select2({
        placeholder: "Seleccione Sala",
        width: "resolve",
        theme: "bootstrap4",
    });
    $("#destino").select2({
        placeholder: "Seleccione Destino",
        width: "resolve",
        theme: "bootstrap4",
    });
    toastr.options = {
        positionClass: "toast-position",
        containerId: "toast-container",
    };
    var newDestinoCreated = sessionStorage.getItem("newDestinoCreated");
    var newCorteCreated = sessionStorage.getItem("newCorteCreated");
    var newCalibreCreated = sessionStorage.getItem("newCalibreCreated");
    var newCalidadCreated = sessionStorage.getItem("newCalidadCreated");
    var planillaModified = sessionStorage.getItem("planillaModified");

    if (newDestinoCreated === "true") {
        toastr.info("Nuevo destino creado");
        sessionStorage.removeItem("newDestinoCreated");
    }
    if (newCorteCreated === "true") {
        toastr.info("Nuevo corte creado");
        sessionStorage.removeItem("newCorteCreated");
    }
    if (newCalibreCreated === "true") {
        toastr.info("Nuevo calibre creado");
        sessionStorage.removeItem("newCalibreCreated");
    }
    if (newCalidadCreated === "true") {
        toastr.info("Nueva calidad creada");
        sessionStorage.removeItem("newCalidadCreated");
    }
    if (planillaModified === "true") {
        toastr.success("Se actualizo la planilla correctamente");
        sessionStorage.removeItem("planillaModified");
    }

    $("#formPrincipal").submit(function (event) {
        var cInicial = $('select[name="cInicial"]').val();
        var calibre = $('select[name="calibre"]').val();
        var piezas = $('input[name="piezas"]').val();
        var cFinal = $('select[name="cFinal"]').val();
        var destino = $('select[name="destino"]').val();
        var calidad = $('select[name="calidad"]').val();
        var kilos = $('input[name="kilos"]').val();

        var newCorteIni = $('input[name="newCorteIni"]').val();
        var newCorteFin = $('input[name="newCorteFin"]').val();
        var newCalibre = $('input[name="newCalibre"]').val();
        var newDestino = $('input[name="newDestino"]').val();
        var newCalidad = $('input[name="newCalidad"]').val();

        if (
            !cInicial ||
            !calibre ||
            !piezas ||
            !cFinal ||
            !destino ||
            !calidad ||
            !kilos ||
            (destino === "nuevo" && !newDestino) ||
            (cInicial === "nuevo" && !newCorteIni) ||
            (cFinal === "nuevo" && !newCorteFin) ||
            (calibre === "nuevo" && !newCalibre) ||
            (calidad === "nuevo" && !newCalidad)
        ) {
            toastr.error(
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
                            newCorteIni ||
                            newCorteFin ||
                            newCalibre ||
                            newCalidad
                        ) {
                            if (newDestino) {
                                sessionStorage.setItem(
                                    "newDestinoCreated",
                                    "true"
                                );
                            }
                            if (newCorteIni || newCorteFin) {
                                sessionStorage.setItem(
                                    "newCorteCreated",
                                    "true"
                                );
                            }
                            if (newCalibre) {
                                sessionStorage.setItem(
                                    "newCalibreCreated",
                                    "true"
                                );
                            }
                            if (newCalidad) {
                                sessionStorage.setItem(
                                    "newCalidadCreated",
                                    "true"
                                );
                            }
                            location.reload(true);
                        }

                        actualizarTabla(
                            response.planilla,
                            response.subtotal,
                            response.total
                        );
                        toastr.success("Registro ingresado");
                    } else if (response.errores) {
                        if (response.errores.errorDestino) {
                            toastr.error(response.errores.errorDestino);
                        }
                        if (response.errores.errorCorte) {
                            toastr.error(response.errores.errorCorte);
                        }
                        if (response.errores.errorCalibre) {
                            toastr.error(response.errores.errorCalibre);
                        }
                        if (response.errores.errorCalidad) {
                            toastr.error(response.errores.errorCalidad);
                        }
                    }
                },
                error: function () {
                    toastr.error("Error al procesar la solicitud");
                },
            });
        }
    });

    function actualizarTabla(planilla, subtotales, total) {
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
                registro.destino +
                "</td>" +
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
                '<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"data-id="' +
                registro.cod_reg +
                '" />' +
                '<label class="form-check-label" for="flexCheckDefault"></label>' +
                "</div>" +
                "</td>" +
                '<td><a href="#" class="btn btn-primary btn-editar"data-id="' +
                registro.cod_reg +
                '">Editar</a></td>' +
                "</tr>";

            tabla.append(nuevaFila);
        });
        var cuerpoTabla = $("#totales tbody");

        // Limpiar el contenido actual de la tabla
        cuerpoTabla.empty();

        // Agregar nuevas filas a la tabla con los datos actualizados
        $.each(subtotales, function (index, subtotal) {
            var nuevaFila =
                "<tr>" +
                "<td>" +
                subtotal.cFinal +
                "</td>" +
                "<td>" +
                subtotal.subtotalPiezas +
                "</td>" +
                "<td>" +
                parseFloat(subtotal.subtotalKilos).toFixed(2) +
                "</td>" +
                "</tr>";

            cuerpoTabla.append(nuevaFila);
        });

        // Agregar fila de total
        var filaTotal =
            '<tr id="filaTotal">' +
            "<th>Total</th>" +
            "<td>" +
            total[0].totalPiezas +
            "</td>" +
            "<td>" +
            parseFloat(total[0].totalKilos).toFixed(2) +
            "</td>" +
            "</tr>";

        cuerpoTabla.append(filaTotal);
    }
    $("#btnGuardarPlanilla").on("click", function (event) {
        event.preventDefault();

        var salaValue = $("#sala").val();
        var dotacionValue = $("#dotacion").val();

        if (salaValue === null || salaValue === "" || dotacionValue === "") {
            toastr.error(
                "Por favor completa todos los campos sala y dotacion."
            );
            return; // Detener el envío del formulario si hay campos vacíos
        }

        $.ajax({
            type: "POST",
            url: baseUrl + "/guardar-planilla",
            data: $("#formEntrega").serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    sessionStorage.setItem("planillaSaved", "true");

                    window.location.href = baseUrl + "/inicio";
                    window.removeEventListener(
                        "beforeunload",
                        beforeUnloadHandler
                    );
                } else {
                    toastr.error(
                        "Error al guardar la planilla:",
                        response.error
                    );
                }
            },
            error: function (xhr, status, error) {
                toastr.error("Error en la solicitud:", error);
            },
        });
    });
    $("#formEditarReg").submit(function (e) {
        e.preventDefault(); // Evitar el envío normal del formulario
        var formData = $(this).serialize(); // Obtener datos del formulario

        // Realizar la solicitud Ajax
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: formData,
            success: function (response) {
                console.log(response);

                actualizarTabla(
                    response.planilla,
                    response.subtotal,
                    response.total
                );
                $("#modalEditar").modal("hide");
                toastr.success("Registro editado correctamente");
            },
            error: function (error) {
                console.log(error);
            },
        });
    });

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
    });
    $("#btnBorrarSeleccionados").on("click", function () {
        var planillaId = $(this).data("planilla-id");
        var checkboxesSeleccionados = $("input:checked", "tbody");

        // Almacena los IDs de las filas seleccionadas
        var idsAEliminar = checkboxesSeleccionados
            .map(function () {
                return $(this).data("id");
            })
            .get();

        console.log(idsAEliminar);

        var token = $('meta[name="csrf-token"]').attr("content");

        // Agrega el token CSRF a los datos de la solicitud
        var datosSolicitud = {
            idPlanilla: planillaId,
            ids: idsAEliminar,
            _token: token,
        };
        // Realiza la solicitud AJAX para eliminar las filas en el servidor
        $.ajax({
            type: "POST",
            url: baseUrl + "/eliminar-registro", // Reemplaza con la ruta correcta
            data: datosSolicitud,
            success: function (response) {
                console.log(response);

                if (response.success) {
                    actualizarTabla(
                        response.planilla,
                        response.subtotal,
                        response.total
                    );
                    toastr.success(
                        "Registros seleccionados eliminados correctamente"
                    );
                } else {
                    toastr.error("No hay registros seleccionados");
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
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
    $("#cInicial").val(null).trigger("change");
    $("#cFinal").val(null).trigger("change");
    $("#calibre").val(null).trigger("change");
    $("#calidad").val(null).trigger("change");
    $("#destino").val(null).trigger("change");
    toastr.info("Formulario impiado");
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

        if (Object.keys(modifiedFields).length > 0) {
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: modifiedFields,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        sessionStorage.setItem("planillaModified", "true");

                        var currentUrl = window.location.href;
                        var newUrl;

                        if (currentUrl.includes("?")) {
                            newUrl = currentUrl + "&tab=detalle";
                        } else {
                            newUrl = currentUrl + "?tab=detalle";
                        }

                        window.location.href = newUrl;
                    } else {
                        toastr.error("Error al insertar el dato");
                    }
                },
                error: function () {
                    toastr.error("Error al procesar la solicitud");
                },
            });
        } else {
            toastr.error("No se han realizado cambios en el formulario.");
        }
    });
});
