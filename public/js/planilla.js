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
        event.preventDefault();

        // Obtener los valores actuales de productividad y rendimiento
        const productividad = parseFloat($("#productividad").text()) || 0;
        const rendimiento =
            parseFloat($("#rendimientoGeneral").text().replace("%", "")) || 0;

        // Agregar los campos al formulario
        const form = $(this);
        form.append(
            '<input type="hidden" name="productividad" value="' +
                productividad +
                '">'
        );
        form.append(
            '<input type="hidden" name="rendimiento" value="' +
                rendimiento +
                '">'
        );

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

            Toast.show("Guardando registro...");
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    Toast.hide();
                    console.log("Respuesta del servidor:", response);
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
                    Toast.hide();
                    toastr.error("Error al procesar la solicitud");
                },
            });
        }
        actualizarCamposRecepcion();
        actualizarIndicadores();
    });

    function actualizarTabla(planilla, subtotales, total) {
        // Actualizar campos de recepción primero
        actualizarCamposRecepcion();

        // Actualizar indicadores una sola vez
        actualizarIndicadores();

        console.log("Actualizando tabla de registros con:", planilla);

        var tablaRegistros = $("#tabla-registros table tbody");
        tablaRegistros.empty();

        // Agregar registros a la tabla principal
        if (planilla && planilla.length > 0) {
            let contador = 1;
            planilla.forEach(function (registro) {
                var nuevaFila = `
                    <tr>
                        <th>${contador}</th>
                        <td>${registro.cInicial}</td>
                        <td>${registro.cFinal}</td>
                        <td>${registro.destino}</td>
                        <td>${registro.calibre}</td>
                        <td>${registro.calidad}</td>
                        <td>${registro.piezas}</td>
                        <td>${registro.kilos}</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" data-id="${registro.cod_reg}" />
                                <label class="form-check-label" for="flexCheckDefault"></label>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="btn btn-primary btn-editar" data-id="${registro.cod_reg}">Editar</a>
                        </td>
                    </tr>
                `;
                tablaRegistros.append(nuevaFila);
                contador++;
            });
        }

        // Actualizar también la tabla de totales
        var tablaTotales = $("#totales tbody");
        tablaTotales.empty();

        // Agregar filas de subtotales
        $.each(subtotales, function (index, subtotal) {
            var nuevaFila = `
                <tr>
                    <td class="px-3">${subtotal.corte_final}</td>
                    <td class="px-3">${subtotal.calidad}</td>
                    <td class="text-end px-3">${number_format(
                        subtotal.total_piezas,
                        0,
                        ".",
                        ","
                    )}</td>
                    <td class="text-end px-3">${number_format(
                        subtotal.total_kilos,
                        2,
                        ".",
                        ","
                    )}</td>
                    <td class="text-end px-3">${number_format(
                        subtotal.porcentaje_del_total,
                        2,
                        ".",
                        ","
                    )}%</td>
                </tr>
            `;
            tablaTotales.append(nuevaFila);
        });

        // Agregar fila de total
        if (total && total.length > 0) {
            var filaTotalHtml = `
                <tr id="filaTotal" class="table-secondary fw-bold">
                    <th class="px-3">${total[0].corte_final}</th>
                    <th class="px-3">${total[0].calidad}</th>
                    <td class="text-end px-3" id="totalPiezas">${number_format(
                        total[0].total_piezas,
                        0,
                        ".",
                        ","
                    )}</td>
                    <td class="text-end px-3" id="totalKilos">${number_format(
                        total[0].total_kilos,
                        2,
                        ".",
                        ","
                    )}</td>
                    <td class="text-end px-3" id="totalPorcentaje">${number_format(
                        total[0].porcentaje_del_total,
                        2,
                        ".",
                        ","
                    )}%</td>
                </tr>
            `;
            tablaTotales.append(filaTotalHtml);

            // Actualizar campos de recepción
            const totalPiezas = total[0].total_piezas;
            const totalKilos = total[0].total_kilos;

            // Actualizar campos de recepción según el tipo de conteo
            const tipoConteo = $('input[name="tipo_conteo"]:checked').val();

            // Siempre actualizar kilos y deshabilitarlo
            $("#kilosRecepcion").val(totalKilos).prop("disabled", true);

            if (tipoConteo === "piezas") {
                $("#piezasRecepcion").val(totalPiezas).prop("disabled", true);
                $("#cajasRecepcion").val("").prop("disabled", true);
            } else if (tipoConteo === "cajas") {
                $("#cajasRecepcion").prop("disabled", false);
                $("#piezasRecepcion").val("").prop("disabled", true);
            }
        }
    }

    // Función auxiliar para formatear números
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
            dec = typeof dec_point === "undefined" ? "." : dec_point,
            s = "",
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return "" + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || "").length < prec) {
            s[1] = s[1] || "";
            s[1] += new Array(prec - s[1].length + 1).join("0");
        }
        return s.join(dec);
    }

    $("#btnGuardarPlanilla").on("click", function (event) {
        event.preventDefault();

        var salaValue = $("#sala").val();
        var dotacionValue = $("#dotacion").val();
        var tipoConteo = $('input[name="tipo_conteo"]:checked').val();
        var kilosEntrega = parseFloat($("#kilosEntrega").val()) || 0;
        var kilosRecepcion = parseFloat($("#kilosRecepcion").val()) || 0;
        var cajasEntrega = parseInt($("#cajasEntrega").val()) || 0;
        var cajasRecepcion = parseInt($("#cajasRecepcion").val()) || 0;
        var piezasEntrega = parseInt($("#piezasEntrega").val()) || 0;
        var piezasRecepcion = parseInt($("#piezasRecepcion").val()) || 0;
        const horaTermino = $("#hora_termino").val();
        // Obtener productividad y rendimiento
        const productividad = parseFloat($("#productividad").text()) || 0;
        console.log(productividad, "productividad");
        const rendimiento =
            parseFloat($("#rendimientoGeneral").text().replace("%", "")) || 0;
        console.log(rendimiento, "rendimiento");
        var errores = [];

        // Primera validación: hora de término
        if (!horaTermino) {
            console.log(horaTermino, "horaTermino");
            errores.push("Por favor, ingrese la hora de término");
        }

        // Validar sala y dotación
        if (!salaValue) {
            errores.push("Debe seleccionar una sala");
        }
        if (!dotacionValue) {
            errores.push("Debe ingresar la dotación");
        }

        // Validar tipo de conteo
        if (!tipoConteo) {
            errores.push("Debe seleccionar un tipo de conteo (Cajas o Piezas)");
        }

        // Validar kilos (siempre requeridos y no pueden ser 0)
        if (kilosEntrega <= 0 || kilosRecepcion <= 0) {
            errores.push("Los kilos deben ser mayores a 0");
        }

        // Validar según tipo de conteo
        if (tipoConteo === "cajas") {
            if (cajasEntrega <= 0 || cajasRecepcion <= 0) {
                errores.push("Las cajas deben ser mayores a 0");
            }
        } else if (tipoConteo === "piezas") {
            if (piezasEntrega <= 0 || piezasRecepcion <= 0) {
                errores.push("Las piezas deben ser mayores a 0");
            }
        }

        // Si hay errores, mostrarlos y detener el envío
        if (errores.length > 0) {
            errores.forEach(function (error) {
                toastr.error(error);
            });
            return;
        }

        // Si pasa todas las validaciones, enviar el formulario
        Toast.show("Guardando planilla...");
        $.ajax({
            type: "POST",
            url: baseUrl + "/guardar-planilla",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: function () {
                // Asegurarnos que los valores de recepción estén en el formulario
                $("#kilos_recepcion").val($("#kilosRecepcion").val());
                $("#piezas_recepcion").val($("#piezasRecepcion").val());

                console.log("Valores de recepción:", {
                    kilos: $("#kilosRecepcion").val(),
                    piezas: $("#piezasRecepcion").val(),
                });
            },
            data:
                $("#formEntrega").serialize() +
                "&productividad=" +
                productividad +
                "&rendimiento=" +
                rendimiento +
                "&kilos_recepcion=" +
                $("#kilosRecepcion").val() +
                "&piezas_recepcion=" +
                $("#piezasRecepcion").val(),
            dataType: "json",
            success: function (response) {
                Toast.hide();
                if (response.success) {
                    toastr.success("Planilla guardada correctamente");
                    sessionStorage.setItem("planillaSaved", "true");
                    window.location.href = baseUrl + "/inicio";
                    window.removeEventListener(
                        "beforeunload",
                        beforeUnloadHandler
                    );
                } else {
                    toastr.error(
                        "Error al guardar la planilla: " + response.mensaje
                    );
                }
            },
            error: function (xhr, status, error) {
                console.log("Error detallado:", {
                    status: status,
                    error: error,
                    response: xhr.responseJSON,
                });
                Toast.hide();
                toastr.error("Error en la solicitud: " + error);
            },
        });
    });
    $("#formEditarReg").submit(function (e) {
        e.preventDefault(); // Evitar el envío normal del formulario
        var formData = $(this).serialize(); // Obtener datos del formulario

        // Mostrar el toast de carga
        Toast.show("Editando registro...");

        // Realizar la solicitud Ajax
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: formData,
            success: function (response) {
                // Ocultar el toast de carga
                Toast.hide();
                console.log(response);
                actualizarCamposRecepcion();
                actualizarIndicadores();
                actualizarTabla(
                    response.planilla,
                    response.subtotal,
                    response.total
                );
                $("#modalEditar").modal("hide");
                toastr.success("Registro editado correctamente");
            },
            error: function (error) {
                // Ocultar el toast de carga en caso de error
                Toast.hide();
                console.log(error);
                toastr.error("Error al editar el registro");
            },
        });
    });

    $("#formularioDetalle").hide();

    $(".nav-link").on("click", function () {
        $(".nav-link").removeClass("active");
        $(this).addClass("active");

        var opcionSeleccionada = $(this).text().trim();

        if (opcionSeleccionada === "Registro") {
            $("#formularioDetalle").hide();
            $("#formPrincipal").show();
        } else if (opcionSeleccionada === "Editar") {
            $("#formPrincipal").hide();
            $("#formularioDetalle").show();
        }
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
        Toast.show("Eliminando registros...");
        $.ajax({
            type: "POST",
            url: baseUrl + "/eliminar-registro", // Reemplaza con la ruta correcta
            data: datosSolicitud,
            success: function (response) {
                Toast.hide();
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
                Toast.hide();
                console.log(error);
            },
        });
        actualizarIndicadores();
        actualizarCamposRecepcion();
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

    // Ocultar solo los contenedores de recepción y entrega inicialmente
    $("#contenedor-entrega, #contenedor-recepcion").hide();

    // Si hay un tipo de conteo seleccionado al cargar, mostrar los contenedores
    const tipoConteoInicial = $('input[name="tipo_conteo"]:checked').val();
    if (tipoConteoInicial) {
        $("#contenedor-entrega, #contenedor-recepcion").show();
        // Usar los IDs específicos
        const totalPiezas = $("#totalPiezas").text().trim().replace(/,/g, "");
        const totalKilos = $("#totalKilos").text().trim().replace(/,/g, "");

        console.log("Texto piezas:", $("#totalPiezas").text());
        console.log("Texto kilos:", $("#totalKilos").text());
        console.log("Total piezas después de limpieza:", totalPiezas);
        console.log("Total kilos después de limpieza:", totalKilos);

        if (tipoConteoInicial === "cajas") {
            $("#entrega_cajas, #recepcion_cajas").show();
            $("#entrega_piezas, #recepcion_piezas").hide();
            $("#cajasRecepcion").prop("disabled", false);
            $("#piezasRecepcion").val("").prop("disabled", true);
        } else {
            $("#entrega_cajas, #recepcion_cajas").hide();
            $("#entrega_piezas, #recepcion_piezas").show();
            $("#piezasRecepcion").val(totalPiezas).prop("disabled", true);
        }
        $("#kilosRecepcion").val(totalKilos).prop("disabled", true);
    }

    // Manejar el cambio en los radio buttons
    $('input[name="tipo_conteo"]').change(function () {
        const tipo = $(this).val();
        $("#contenedor-entrega, #contenedor-recepcion").show(); // Mostrar los contenedores

        const totalPiezas = $("#totalPiezas").text().trim().replace(/,/g, "");
        const totalKilos = $("#totalKilos").text().trim().replace(/,/g, "");

        console.log("Cambio de tipo de conteo:", tipo);
        console.log("Total piezas:", totalPiezas);
        console.log("Total kilos:", totalKilos);

        if (tipo === "cajas") {
            $("#entrega_cajas, #recepcion_cajas").show();
            $("#entrega_piezas, #recepcion_piezas").hide();
            $("#cajasRecepcion").prop("disabled", false);
            $("#piezasRecepcion").val("").prop("disabled", true);
        } else {
            $("#entrega_cajas, #recepcion_cajas").hide();
            $("#entrega_piezas, #recepcion_piezas").show();
            $("#piezasRecepcion").val(totalPiezas).prop("disabled", true);
        }
        $("#kilosRecepcion").val(totalKilos).prop("disabled", true);
    });

    // Validación del formulario
    $("#formEntrega").submit(function (e) {
        e.preventDefault();

        // Si todas las validaciones pasan, enviamos el formulario
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    toastr.success("Planilla guardada correctamente");
                    window.location.href = baseUrl + "/inicio";
                } else {
                    if (response.mensaje) {
                        toastr.error(response.mensaje);
                    } else if (response.message) {
                        toastr.error(response.message);
                    } else {
                        toastr.error("Error al guardar la planilla");
                    }
                }
            },
            error: function (xhr, status, error) {
                // Mejorado el manejo de errores
                if (
                    xhr.responseJSON &&
                    (xhr.responseJSON.mensaje || xhr.responseJSON.message)
                ) {
                    toastr.error(
                        xhr.responseJSON.mensaje || xhr.responseJSON.message
                    );
                } else {
                    toastr.error("Error en el servidor");
                }
            },
        });
    });

    // Función para cargar los tiempos muertos
    function cargarTiemposMuertos() {
        const idPlanilla = $('input[name="idPlanilla"]').val();

        Toast.show("Cargando tiempos muertos...");
        $.ajax({
            type: "GET",
            url: baseUrl + "/obtener-tiempos-muertos/" + idPlanilla,
            success: function (response) {
                Toast.hide();
                if (response.success) {
                    let html = `
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light sticky-top bg-light">
                                    <tr>
                                        <th style="width: 35%">Causa</th>
                                        <th style="width: 20%">Inicio</th>
                                        <th style="width: 20%">Término</th>
                                        <th style="width: 15%">Duración (min)</th>
                                        <th style="width: 10%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    if (
                        response.tiemposMuertos &&
                        response.tiemposMuertos.length > 0
                    ) {
                        response.tiemposMuertos.forEach(function (tiempo) {
                            html += `
                                <tr>
                                    <td>${tiempo.causa || ""}</td>
                                    <td>${
                                        formatearHora(tiempo.hora_inicio) || ""
                                    }</td>
                                    <td>${
                                        formatearHora(tiempo.hora_termino) || ""
                                    }</td>
                                    <td class="text-end">${
                                        tiempo.duracion_minutos || ""
                                    }</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm eliminar-tiempo" 
                                                data-id="${
                                                    tiempo.cod_tiempo_muerto
                                                }" 
                                                title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html +=
                            '<tr><td colspan="5" class="text-center">No hay tiempos muertos registrados</td></tr>';
                    }

                    html += "</tbody></table></div>";
                    $("#listaTiemposMuertos").html(html);
                }
            },
            error: function () {
                Toast.hide();
                toastr.error("Error al cargar los tiempos muertos");
            },
        });
        actualizarIndicadores();
    }

    // Manejador para eliminar tiempo muerto
    $(document).on("click", ".eliminar-tiempo", function () {
        const idTiempoMuerto = $(this).data("id");
        console.log("ID a eliminar:", idTiempoMuerto); // Para debug

        if (confirm("¿Está seguro de eliminar este tiempo muerto?")) {
            $.ajax({
                type: "DELETE",
                url: `${baseUrl}/eliminar-tiempo-muerto/${idTiempoMuerto}`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success("Tiempo muerto eliminado correctamente");
                        cargarTiemposMuertos();
                    } else {
                        toastr.error(
                            response.message ||
                                "Error al eliminar el tiempo muerto"
                        );
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error); // Para debug
                    toastr.error("Error al procesar la solicitud");
                },
            });
        }
    });

    // Función para formatear la hora
    function formatearHora(horaSQL) {
        if (!horaSQL) return "";
        try {
            // Asumiendo que horaSQL viene en formato "HH:mm:ss" o "HH:mm:ss.000"
            return horaSQL.split(".")[0].substring(0, 5); // Toma solo HH:mm
        } catch (e) {
            console.error("Error al formatear hora:", e);
            return horaSQL;
        }
    }

    // Manejar el modal
    $("#modalTiemposMuertos").on("show.bs.modal", function () {
        cargarTiemposMuertos();
    });

    // Manejar el formulario de tiempos muertos
    $("#formTiemposMuertos").on("submit", function (e) {
        e.preventDefault();

        const horaInicio = $("#horaInicio").val();
        const horaTermino = $("#horaTermino").val();

        // Calcular duración en minutos
        const inicio = new Date(`2000/01/01 ${horaInicio}`);
        const termino = new Date(`2000/01/01 ${horaTermino}`);
        let duracionMinutos = Math.round((termino - inicio) / (1000 * 60));

        if (duracionMinutos < 0) {
            duracionMinutos += 24 * 60;
        }

        const formData =
            $(this).serialize() + `&duracion_minutos=${duracionMinutos}`;

        $.ajax({
            type: "POST",
            url: baseUrl + "/guardar-tiempo-muerto",
            data: formData,
            success: function (response) {
                if (response.success) {
                    toastr.success("Tiempo muerto registrado correctamente");
                    $("#formTiemposMuertos")[0].reset();

                    cargarTiemposMuertos();
                } else {
                    toastr.error(
                        response.message ||
                            "Error al registrar el tiempo muerto"
                    );
                }
            },
            error: function () {
                toastr.error("Error al procesar la solicitud");
            },
        });
    });

    // Función para calcular el tiempo total trabajado en horas
    function calcularTiempoTrabajado() {
        return new Promise((resolve) => {
            const planillaData = document.getElementById("planillaData");
            const idPlanilla = planillaData.dataset.idPlanilla;

            console.log(
                "Obteniendo tiempos muertos para planilla:",
                idPlanilla
            );

            // Primero obtener los tiempos muertos
            $.ajax({
                type: "GET",
                url: baseUrl + "/obtener-tiempos-muertos/" + idPlanilla,
                beforeSend: function () {
                    console.log("Solicitando tiempos muertos a:", this.url);
                },
                success: function (response) {
                    console.log("Respuesta tiempos muertos:", response);

                    const horaInicioPlanilla = planillaData.dataset.horaInicio;
                    const horaTerminoPlanilla =
                        planillaData.dataset.horaTermino;

                    // Calcular tiempo total en minutos
                    const [horaInicioHH, horaInicioMM] =
                        horaInicioPlanilla.split(":");
                    const [horaFinHH, horaFinMM] =
                        horaTerminoPlanilla.split(":");

                    let minutosInicio =
                        parseInt(horaInicioHH) * 60 + parseInt(horaInicioMM);
                    let minutosFin =
                        parseInt(horaFinHH) * 60 + parseInt(horaFinMM);

                    if (minutosFin < minutosInicio) {
                        minutosFin += 24 * 60;
                    }

                    const tiempoTotalMinutos = minutosFin - minutosInicio;
                    let tiempoMuertoTotal = 0;

                    if (response.success && response.tiemposMuertos) {
                        response.tiemposMuertos.forEach(function (tiempo) {
                            const duracionMinutos =
                                parseInt(tiempo.duracion_minutos) || 0;
                            tiempoMuertoTotal += duracionMinutos;
                            console.log("Tiempo muerto encontrado:", {
                                id: tiempo.cod_tiempo_muerto,
                                duracion: duracionMinutos,
                            });
                        });
                    }

                    const tiempoEfectivoMinutos =
                        tiempoTotalMinutos - tiempoMuertoTotal;
                    console.log("Cálculo final:", {
                        tiempoTotal: tiempoTotalMinutos,
                        tiempoMuerto: tiempoMuertoTotal,
                        tiempoEfectivo: tiempoEfectivoMinutos,
                    });

                    resolve(tiempoEfectivoMinutos / 60);
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener tiempos muertos:", {
                        status: status,
                        error: error,
                        response: xhr.responseText,
                    });
                    // En caso de error, usar el tiempo total sin descuentos
                    const tiempoTotalMinutos = minutosFin - minutosInicio;
                    resolve(tiempoTotalMinutos / 60);
                },
            });
        });
    }

    // Función para calcular y actualizar los indicadores
    async function actualizarIndicadores() {
        const kilosEntrega = parseFloat($("#kilosEntrega").val()) || 0;
        const kilosRecepcion = parseFloat($("#kilosRecepcion").val()) || 0;
        const dotacion = parseInt($("#dotacion").val()) || 1;
        const tiempoTrabajado = await calcularTiempoTrabajado();

        console.log("Valores para cálculo:", {
            kilosEntrega,
            kilosRecepcion,
            dotacion,
            tiempoTrabajado,
        });

        // Calcular Rendimiento General
        if (kilosEntrega > 0) {
            const rendimiento = (kilosRecepcion / kilosEntrega) * 100;
            $("#rendimientoGeneral").text(rendimiento.toFixed(2) + "%");
        }

        // Calcular Productividad
        if (dotacion > 0 && tiempoTrabajado > 0) {
            const productividad = kilosRecepcion / (dotacion * tiempoTrabajado);
            $("#productividad").text(
                productividad.toFixed(2) + " kg/persona/hora"
            );
        }
    }

    // Inicialización
    $(document).ready(async function () {
        await actualizarIndicadores();
    });

    // Actualizar cuando cambian los tiempos muertos
    $("#modalTiemposMuertos").on("hidden.bs.modal", async function () {
        await actualizarIndicadores();
    });

    // Agregar listeners para todos los eventos que afectan los indicadores
    $(document).ready(function () {
        // Eventos de cambio en inputs directos
        $("#kilosEntrega, #kilosRecepcion, #dotacion, #hora_termino").on(
            "change input",
            function () {
                actualizarIndicadores();
            }
        );

        // Evento para cuando se agrega o elimina un tiempo muerto
        $("#listaTiemposMuertos").on("DOMSubtreeModified", function () {
            actualizarIndicadores();
        });

        // Evento para cuando se modifica un tiempo muerto existente
        $(document).on("change", ".tiempo-muerto-duracion", function () {
            actualizarIndicadores();
        });

        // Actualizar cuando cambia el tipo de conteo
        $('input[name="tipo_conteo"]').on("change", function () {
            actualizarIndicadores();
        });

        // Actualizar al cargar la página
        actualizarIndicadores();
    });

    // Función para actualizar los campos de recepción
    function actualizarCamposRecepcion() {
        const totalKilos =
            parseFloat($("#totalKilos").text().replace(/,/g, "")) || 0;
        const totalPiezas =
            parseInt($("#totalPiezas").text().replace(/,/g, "")) || 0;

        // Actualizar campos de recepción según el tipo de conteo
        const tipoConteo = $('input[name="tipo_conteo"]:checked').val();

        // Siempre actualizar kilos y deshabilitarlo
        $("#kilosRecepcion").val(totalKilos).prop("disabled", true);

        if (tipoConteo === "piezas") {
            $("#piezasRecepcion").val(totalPiezas).prop("disabled", true);
            $("#cajasRecepcion").val("").prop("disabled", true);
        } else if (tipoConteo === "cajas") {
            $("#cajasRecepcion").prop("disabled", false);
            $("#piezasRecepcion").val("").prop("disabled", true);
        }
    }

    $(document).ready(function () {
        calcularTiempoTrabajado();
        actualizarCamposRecepcion();
        actualizarIndicadores();
    });
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
