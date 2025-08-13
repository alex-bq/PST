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

            // En lugar de actualizar directamente, llamamos a la función que excluye los desechos
            actualizarCamposRecepcion();
        }

        // Calcular y mostrar los kilos premium
        calcularKilosPremium();
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
        var piezasEntrega = parseInt($("#piezasEntrega").val()) || 0;
        var piezasRecepcion = parseInt($("#piezasRecepcion").val()) || 0;
        const horaTermino = $("#hora_termino").val();
        const tipoPlanilla = parseInt($("#tipo_planilla").val());
        const embolsadoTerminado =
            parseFloat($("#embolsadoTerminado").val()) || 0;
        const kilosTerminado = parseFloat($("#kilosTerminado").val()) || 0;
        const TIPO_PORCION = 2;
        const TIPO_FILETE = 1;

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

        if (tipoPlanilla === TIPO_FILETE) {
            // Para Filete, validar que al menos uno de los dos (piezas o kilos) sea mayor a 0
            if ((piezasEntrega <= 0 && kilosEntrega <= 0) || (piezasRecepcion <= 0 && kilosRecepcion <= 0)) {
                errores.push("Debe ingresar al menos piezas o kilos mayores a 0");
            }
        } else if (tipoPlanilla === TIPO_PORCION) {
            // Para planillas tipo porción no validamos piezas
            if (kilosEntrega <= 0 || kilosRecepcion <= 0) {
                errores.push("Los kilos deben ser mayores a 0");
            }
            if (embolsadoTerminado <= 0 || kilosTerminado <= 0) {
                errores.push(
                    "El embolsado y los kilos terminados deben ser mayores a 0"
                );
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
        calcularTiempoTrabajado().then((tiempoTrabajadoHoras) => {
            $.ajax({
                type: "POST",
                url: baseUrl + "/guardar-planilla",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
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
                    $("#piezasRecepcion").val() +
                    "&embolsado_terminado=" +
                    embolsadoTerminado +
                    "&kilos_terminado=" +
                    kilosTerminado +
                    "&tiempo_trabajado=" +
                    tiempoTrabajadoHoras,
                dataType: "json",
                success: function (response) {
                    Toast.hide();
                    if (response.success) {
                        toastr.success("Planilla guardada correctamente");
                        sessionStorage.setItem("planillaSaved", "true");

                        // ✅ SOLUCIÓN: Redirección condicional basada en origen
                        const origen = sessionStorage.getItem("modal_origen");
                        const informeUrl =
                            sessionStorage.getItem("informe_url");

                        if (origen === "informe" && informeUrl) {
                            // Limpiar sessionStorage
                            sessionStorage.removeItem("modal_origen");
                            sessionStorage.removeItem("informe_url");

                            // Volver al informe (navegando en el iframe principal, no en toda la ventana)
                            console.log(
                                "🔄 Redirigiendo al informe dentro del iframe principal:",
                                informeUrl
                            );

                            // Acceder al iframe principal desde el modal
                            try {
                                const iframeContent =
                                    window.parent.parent.document.getElementById(
                                        "iframeContent"
                                    );
                                if (iframeContent) {
                                    iframeContent.src = informeUrl;
                                    // También actualizar sessionStorage del contexto principal
                                    window.parent.parent.sessionStorage.setItem(
                                        "lastVisitedPage",
                                        informeUrl
                                    );
                                    console.log(
                                        "✅ Navegación exitosa dentro del iframe principal"
                                    );
                                } else {
                                    console.log(
                                        "⚠️ No se encontró iframe principal, usando redirección normal"
                                    );
                                    window.parent.location.href = informeUrl;
                                }
                            } catch (error) {
                                console.log(
                                    "⚠️ Error accediendo al iframe principal, usando redirección normal:",
                                    error
                                );
                                window.parent.location.href = informeUrl;
                            }
                        } else {
                            // Comportamiento original: ir al inicio
                            console.log("🔄 Redirigiendo al inicio");
                            window.location.href = baseUrl + "/inicio";
                        }

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

    // Actualizar valores iniciales
    const totalPiezas = $("#totalPiezas").text().trim().replace(/,/g, "");
    const totalKilos = $("#totalKilos").text().trim().replace(/,/g, "");
    $("#piezasRecepcion").val(totalPiezas).prop("disabled", true);
    $("#kilosRecepcion").val(totalKilos).prop("disabled", true);

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
                                        <th style="width: 15%">Departamento</th>
                                        <th style="width: 30%">Causa</th>
                                        <th style="width: 15%">Inicio</th>
                                        <th style="width: 15%">Término</th>
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
                                    <td>${tiempo.departamento || ""}</td>
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
                            '<tr><td colspan="6" class="text-center">No hay tiempos muertos registrados</td></tr>';
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
        console.log("Modal abierto - llamando a cargarDepartamentos"); // Debug
        cargarDepartamentos();
        cargarTiemposMuertos();
    });

    // Función para cargar departamentos
    function cargarDepartamentos() {
        console.log("Cargando departamentos..."); // Debug
        $.ajax({
            type: "GET",
            url: baseUrl + "/obtener-departamentos",
            success: function (response) {
                console.log("Respuesta departamentos:", response); // Debug
                if (response.success) {
                    let options =
                        '<option value="">Seleccione departamento</option>';
                    response.departamentos.forEach(function (depto) {
                        options += `<option value="${depto.cod_departamento}">${depto.nombre}</option>`;
                    });
                    $("#departamento").html(options);
                    console.log("Options generadas:", options); // Debug
                } else {
                    console.error("Error en la respuesta:", response); // Debug
                }
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar departamentos:", error); // Debug
                toastr.error("Error al cargar los departamentos");
            },
        });
    }

    // Manejar el formulario de tiempos muertos
    $("#formTiemposMuertos").on("submit", function (e) {
        e.preventDefault();

        const horaInicio = $("#horaInicio").val();
        const horaTermino = $("#horaTermino").val();

        console.log("Enviando tiempo muerto:", {
            horaInicio: horaInicio,
            horaTermino: horaTermino,
            nombreCampoTermino: $("#horaTermino").attr("name"),
        });

        // Calcular duración en minutos
        const inicio = new Date(`2000/01/01 ${horaInicio}`);
        const termino = new Date(`2000/01/01 ${horaTermino}`);
        let duracionMinutos = Math.round((termino - inicio) / (1000 * 60));

        if (duracionMinutos < 0) {
            duracionMinutos += 24 * 60;
        }

        // Obtener todos los datos del formulario para depuración
        const formDataObj = {};
        const formElements =
            document.getElementById("formTiemposMuertos").elements;
        for (let i = 0; i < formElements.length; i++) {
            const element = formElements[i];
            if (element.name) {
                formDataObj[element.name] = element.value;
            }
        }
        console.log("Datos del formulario:", formDataObj);

        const formData =
            $(this).serialize() + `&duracion_minutos=${duracionMinutos}`;
        console.log("FormData serializado:", formData);

        $.ajax({
            type: "POST",
            url: baseUrl + "/guardar-tiempo-muerto",
            data: formData,
            success: function (response) {
                console.log(
                    "Respuesta del servidor al guardar tiempo muerto:",
                    response
                );
                if (response.success) {
                    toastr.success("Tiempo muerto registrado correctamente");
                    $("#formTiemposMuertos")[0].reset();

                    // Recargar la lista de tiempos muertos
                    cargarTiemposMuertos();

                    // Actualizar los indicadores para reflejar el nuevo tiempo muerto
                    setTimeout(function () {
                        actualizarIndicadores();
                    }, 500);
                } else {
                    console.error("Error en la respuesta:", response);
                    toastr.error(
                        response.message ||
                            "Error al registrar el tiempo muerto"
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("Error al guardar tiempo muerto:", {
                    status: status,
                    error: error,
                    response: xhr.responseText,
                });
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
                "Iniciando cálculo de tiempo trabajado para planilla ID:",
                idPlanilla
            );

            $.ajax({
                type: "GET",
                url: baseUrl + "/obtener-tiempos-muertos/" + idPlanilla,
                success: function (response) {
                    console.log("Respuesta de tiempos muertos:", response);

                    const horaInicioPlanilla = planillaData.dataset.horaInicio;

                    // Usar el valor del input de hora de término en lugar del valor almacenado
                    let horaTerminoPlanilla;
                    const inputHoraTermino =
                        document.getElementById("hora_termino");

                    if (inputHoraTermino && inputHoraTermino.value) {
                        horaTerminoPlanilla = inputHoraTermino.value;
                        console.log(
                            "Usando hora de término del input:",
                            horaTerminoPlanilla
                        );
                    } else {
                        // Usar el valor almacenado como respaldo
                        horaTerminoPlanilla = planillaData.dataset.horaTermino;
                        console.log(
                            "Usando hora de término del dataset:",
                            horaTerminoPlanilla
                        );
                    }

                    // Validar que ambas horas existan
                    if (!horaInicioPlanilla || !horaTerminoPlanilla) {
                        console.error(
                            "Hora de inicio o término no disponible",
                            {
                                inicio: horaInicioPlanilla,
                                termino: horaTerminoPlanilla,
                            }
                        );
                        resolve(0);
                        return;
                    }

                    try {
                        // Convertir las horas a objetos Date del mismo día
                        let inicio = new Date(
                            `2000/01/01 ${horaInicioPlanilla}`
                        );
                        let termino = new Date(
                            `2000/01/01 ${horaTerminoPlanilla}`
                        );

                        console.log("Fechas convertidas:", {
                            horaInicio: horaInicioPlanilla,
                            horaTermino: horaTerminoPlanilla,
                            inicioDate: inicio,
                            terminoDate: termino,
                            inicioTime: inicio.getTime(),
                            terminoTime: termino.getTime(),
                        });

                        // Verificar que las fechas sean válidas
                        if (
                            isNaN(inicio.getTime()) ||
                            isNaN(termino.getTime())
                        ) {
                            console.error("Fechas inválidas", {
                                inicio: horaInicioPlanilla,
                                termino: horaTerminoPlanilla,
                                inicioDate: inicio,
                                terminoDate: termino,
                            });
                            resolve(0);
                            return;
                        }

                        // Si la hora de término es menor que la de inicio, asumimos que es del día siguiente
                        if (termino < inicio) {
                            termino = new Date(
                                `2000/01/02 ${horaTerminoPlanilla}`
                            );
                            console.log(
                                "Ajustando fecha de término al día siguiente:",
                                termino
                            );
                        }

                        // Calcular la diferencia en minutos
                        const tiempoTotalMinutos = Math.round(
                            (termino - inicio) / (1000 * 60)
                        );
                        console.log(
                            "Tiempo total en minutos:",
                            tiempoTotalMinutos
                        );

                        let tiempoMuertoTotal = 0;

                        if (response.success && response.tiemposMuertos) {
                            console.log(
                                "Procesando tiempos muertos:",
                                response.tiemposMuertos.length,
                                "registros"
                            );

                            response.tiemposMuertos.forEach(function (
                                tiempo,
                                index
                            ) {
                                console.log(
                                    `Tiempo muerto #${index + 1}:`,
                                    tiempo
                                );

                                // Verificar que los valores de tiempo existan y sean válidos
                                if (
                                    !tiempo.hora_inicio ||
                                    !tiempo.hora_termino
                                ) {
                                    console.error(
                                        `Tiempo muerto #${
                                            index + 1
                                        } tiene valores de tiempo faltantes:`,
                                        {
                                            hora_inicio: tiempo.hora_inicio,
                                            hora_termino: tiempo.hora_termino,
                                        }
                                    );
                                    return; // Saltar este tiempo muerto
                                }

                                // Aplicar la misma lógica para los tiempos muertos
                                let inicioTM = new Date(
                                    `2000/01/01 ${tiempo.hora_inicio}`
                                );
                                let terminoTM = new Date(
                                    `2000/01/01 ${tiempo.hora_termino}`
                                );

                                console.log(
                                    `Tiempo muerto #${
                                        index + 1
                                    } fechas convertidas:`,
                                    {
                                        inicioTM: inicioTM,
                                        terminoTM: terminoTM,
                                        inicioTMValid: !isNaN(
                                            inicioTM.getTime()
                                        ),
                                        terminoTMValid: !isNaN(
                                            terminoTM.getTime()
                                        ),
                                    }
                                );

                                // Verificar que las fechas sean válidas
                                if (
                                    isNaN(inicioTM.getTime()) ||
                                    isNaN(terminoTM.getTime())
                                ) {
                                    console.error(
                                        `Tiempo muerto #${
                                            index + 1
                                        } tiene fechas inválidas:`,
                                        {
                                            hora_inicio: tiempo.hora_inicio,
                                            hora_termino: tiempo.hora_termino,
                                            inicioTM: inicioTM,
                                            terminoTM: terminoTM,
                                        }
                                    );
                                    return; // Saltar este tiempo muerto
                                }

                                if (terminoTM < inicioTM) {
                                    terminoTM = new Date(
                                        `2000/01/02 ${tiempo.hora_termino}`
                                    );
                                    console.log(
                                        `Tiempo muerto #${
                                            index + 1
                                        }: Ajustando fecha de término al día siguiente:`,
                                        terminoTM
                                    );
                                }

                                const duracionMinutos = Math.round(
                                    (terminoTM - inicioTM) / (1000 * 60)
                                );

                                console.log(
                                    `Tiempo muerto #${index + 1} duración:`,
                                    duracionMinutos,
                                    "minutos"
                                );

                                if (isNaN(duracionMinutos)) {
                                    console.error(
                                        `Tiempo muerto #${
                                            index + 1
                                        } tiene duración inválida:`,
                                        {
                                            inicioTM: inicioTM,
                                            terminoTM: terminoTM,
                                            diferencia: terminoTM - inicioTM,
                                        }
                                    );
                                    return; // Saltar este tiempo muerto
                                }

                                tiempoMuertoTotal += duracionMinutos;
                                console.log(
                                    `Tiempo muerto acumulado hasta ahora:`,
                                    tiempoMuertoTotal,
                                    "minutos"
                                );
                            });
                        } else {
                            console.log(
                                "No hay tiempos muertos registrados o la respuesta no fue exitosa"
                            );
                        }

                        const tiempoEfectivoMinutos =
                            tiempoTotalMinutos - tiempoMuertoTotal;

                        console.log("Cálculo final:", {
                            tiempoTotal: tiempoTotalMinutos,
                            tiempoMuerto: tiempoMuertoTotal,
                            tiempoEfectivo: tiempoEfectivoMinutos,
                        });

                        // Asegurarse de que el resultado sea un número válido
                        const resultado = isNaN(tiempoEfectivoMinutos)
                            ? 0
                            : tiempoEfectivoMinutos / 60;
                        console.log("Resultado final en horas:", resultado);

                        resolve(resultado);
                    } catch (e) {
                        console.error(
                            "Error en el cálculo del tiempo trabajado:",
                            e
                        );
                        resolve(0);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener tiempos muertos:", error);
                    resolve(0);
                },
            });
        });
    }

    // Función para calcular el rendimiento
    function calcularRendimiento() {
        const tipoPlanilla = parseInt($("#tipo_planilla").val());
        const TIPO_PORCION = 2; // Definir constante para el código de Porción

        const kilosEntrega = parseFloat($("#kilosEntrega").val()) || 0;
        let kilosFinales;

        if (tipoPlanilla === TIPO_PORCION) {
            // Para planillas tipo porción, usar kilos de producto terminado
            kilosFinales = parseFloat($("#kilosTerminado").val()) || 0;
        } else {
            // Para otros tipos de planilla, usar kilos de recepción
            kilosFinales = parseFloat($("#kilosRecepcion").val()) || 0;
        }

        if (kilosEntrega === 0) return 0;

        const rendimiento = (kilosFinales / kilosEntrega) * 100;
        return rendimiento;
    }

    // Función para calcular y actualizar los indicadores
    function actualizarIndicadores() {
        calcularTiempoTrabajado().then((tiempoTrabajadoHoras) => {
            // Actualizar el tiempo trabajado en el dashboard
            const horasEnteras = Math.floor(tiempoTrabajadoHoras);
            const minutos = Math.round(
                (tiempoTrabajadoHoras - horasEnteras) * 60
            );
            $("#tiempoTrabajado").text(`${horasEnteras}h ${minutos}m`);

            // Calcular y actualizar la productividad
            const totalKilos =
                parseFloat($("#totalKilos").text().replace(/,/g, "")) || 0;
            const dotacion = parseInt($("#dotacion").val()) || 1;

            let productividad = 0;
            if (tiempoTrabajadoHoras > 0 && dotacion > 0) {
                productividad = totalKilos / (tiempoTrabajadoHoras * dotacion);
            }

            $("#productividad").text(
                productividad.toFixed(1) + " kg/persona/hora"
            );

            // Calcular y actualizar el rendimiento
            const rendimiento = calcularRendimiento();
            $("#rendimiento").text(rendimiento.toFixed(1) + "%");

            // Calcular y mostrar los kilos premium
            calcularKilosPremium();
        });
    }

    // Inicialización
    $(document).ready(async function () {
        await actualizarIndicadores();
        calcularKilosPremium();
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

        // Actualizar kilos premium al cargar la página
        calcularKilosPremium();

        // Actualizar cuando cambia la tabla
        $("#tabla-registros").on("DOMSubtreeModified", function () {
            calcularKilosPremium();
        });
    });

    // Función para actualizar los campos de recepción
    function actualizarCamposRecepcion() {
        // Obtener los totales de la tabla
        const totalKilos =
            parseFloat($("#totalKilos").text().replace(/,/g, "")) || 0;
        const totalPiezas =
            parseInt($("#totalPiezas").text().replace(/,/g, "")) || 0;

        // Inicializar variables para los desechos
        let kilosDesecho = 0;
        let piezasDesecho = 0;

        // Buscar los desechos en la tabla de totales
        $("#totales tbody tr").each(function () {
            // Obtener el corte final (primera columna)
            const corteFinal = $(this)
                .find("td:eq(0)")
                .text()
                .trim()
                .toUpperCase();
            if (!corteFinal) {
                // Si no encontramos en td, buscar en th
                const corteFinalTh = $(this)
                    .find("th:eq(0)")
                    .text()
                    .trim()
                    .toUpperCase();
                if (
                    corteFinalTh === "DESECHO ORGANICO" ||
                    corteFinalTh === "DESECHO PISO"
                ) {
                    // Obtener los kilos y piezas de esta fila
                    const piezas =
                        parseInt(
                            $(this).find("td:eq(2)").text().replace(/,/g, "")
                        ) || 0;
                    const kilos =
                        parseFloat(
                            $(this).find("td:eq(3)").text().replace(/,/g, "")
                        ) || 0;

                    // Sumar a los totales de desechos
                    piezasDesecho += piezas;
                    kilosDesecho += kilos;
                    console.log(
                        `Desecho encontrado en th: ${corteFinalTh}, Piezas: ${piezas}, Kilos: ${kilos}`
                    );
                }
            } else if (
                corteFinal === "DESECHO ORGANICO" ||
                corteFinal === "DESECHO PISO"
            ) {
                // Obtener los kilos y piezas de esta fila
                const piezas =
                    parseInt(
                        $(this).find("td:eq(2)").text().replace(/,/g, "")
                    ) || 0;
                const kilos =
                    parseFloat(
                        $(this).find("td:eq(3)").text().replace(/,/g, "")
                    ) || 0;

                // Sumar a los totales de desechos
                piezasDesecho += piezas;
                kilosDesecho += kilos;
                console.log(
                    `Desecho encontrado en td: ${corteFinal}, Piezas: ${piezas}, Kilos: ${kilos}`
                );
            }
        });

        // Calcular los totales netos (excluyendo desechos)
        const kilosNetos = totalKilos - kilosDesecho;
        const piezasNetas = totalPiezas - piezasDesecho;

        console.log("Totales originales:", { totalKilos, totalPiezas });
        console.log("Desechos encontrados:", { kilosDesecho, piezasDesecho });
        console.log("Totales netos:", { kilosNetos, piezasNetas });

        // Actualizar los campos de recepción con los valores netos
        $("#kilosRecepcion").val(kilosNetos.toFixed(2)).prop("disabled", true);
        $("#piezasRecepcion")
            .val(Math.round(piezasNetas))
            .prop("disabled", true);
    }

    $(document).ready(function () {
        calcularTiempoTrabajado();
        actualizarCamposRecepcion();
        actualizarIndicadores();
        calcularKilosPremium();
        // Agregar event listener para recalcular cuando cambie la hora de término
        $("#hora_termino").on("change", function () {
            actualizarIndicadores();
        });
    });

    // Eliminar el event listener de los radio buttons
    // y reemplazar por una configuración inicial basada en el tipo de planilla

    const tipoPlanilla = parseInt($("#tipo_planilla").val());
    const TIPO_PORCION = 2; // Definir constante para el código de Porción

    if (tipoPlanilla === TIPO_PORCION || tipoPlanilla === 4) {
        $("#entrega_piezas, #recepcion_piezas").hide();
        $("#piezasEntrega, #piezasRecepcion").prop("required", false);
        $("#producto_terminado").show();
        $("#embolsadoTerminado, #kilosTerminado").prop("required", true);
    } else {
        $("#entrega_piezas, #recepcion_piezas").show();
        $("#piezasEntrega, #piezasRecepcion").prop("required", true);
        $("#producto_terminado").hide();
        $("#embolsadoTerminado, #kilosTerminado").prop("required", false);
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
    var jefeTurnoSelect = document.querySelector('select[name="jefe_turno"]');

    // Guardar los valores iniciales
    var initialFechaTurno = fechaTurno.value;
    var initialTurno = turnoSelect.value;
    var initialSupervisor = supervisorSelect.value;
    var initialPlanillero = planilleroSelect.value;
    var initialJefeTurno = jefeTurnoSelect.value;

    // Función para comprobar si hay cambios
    function checkChanges() {
        var cambios =
            fechaTurno.value !== initialFechaTurno ||
            turnoSelect.value !== initialTurno ||
            supervisorSelect.value !== initialSupervisor ||
            planilleroSelect.value !== initialPlanillero ||
            jefeTurnoSelect.value !== initialJefeTurno;

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

        if (jefeTurnoSelect.value !== initialJefeTurno) {
            modifiedFields.jefe_turno = jefeTurnoSelect.value;
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

    $(jefeTurnoSelect).on("change.select2", function () {
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

// Función para calcular y mostrar los kilos premium
function calcularKilosPremium() {
    // Verificar si la planilla es de tipo "Ahumado" o "Porciones"
    const tipoPlanilla = parseInt($("#tipo_planilla").val());
    if (tipoPlanilla === 2 || tipoPlanilla === 4) {
        return; // No calcular premium para porciones o ahumado
    }

    let kilosPremium = 0;

    // Primero intentamos buscar directamente en la tabla de totales
    console.log("Buscando en tabla de totales...");

    $("#totales tbody tr").each(function () {
        // Obtener el texto de la columna de calidad (segunda columna)
        const calidad = $(this).find("td:eq(1)").text().trim().toLowerCase();
        if (!calidad) {
            // Si no encontramos en td, buscar en th
            const calidadTh = $(this)
                .find("th:eq(1)")
                .text()
                .trim()
                .toLowerCase();
            if (calidadTh) {
                console.log(`Calidad encontrada en th: ${calidadTh}`);
            }
        }

        // Obtener kilos (cuarta columna)
        const kilosText = $(this).find("td:eq(3)").text().trim();
        const kilos = parseFloat(kilosText.replace(/,/g, "")) || 0;

        console.log(
            `Fila totales: Calidad="${calidad}", Kilos=${kilos}, Texto kilos="${kilosText}"`
        );

        // Verificar si la calidad es premium
        if (
            calidad === "premium" ||
            calidad === "a" ||
            calidad === "a+" ||
            calidad.includes("premium") ||
            calidad.includes("a+")
        ) {
            kilosPremium += kilos;
            console.log(`Sumando ${kilos} kg premium, total: ${kilosPremium}`);
        }
    });

    // Si no encontramos nada en totales, intentar con la tabla de registros
    if (kilosPremium === 0) {
        console.log("Intentando con tabla de registros detallados...");

        // Imprimir estructura de la tabla para depuración
        const tablaRegistros = $("#tabla-registros table");
        console.log("Tabla encontrada:", tablaRegistros.length > 0);
        if (tablaRegistros.length > 0) {
            console.log("Encabezados de la tabla:");
            $("#tabla-registros table thead th").each(function (index) {
                console.log(`Columna ${index}: ${$(this).text().trim()}`);
            });

            // Ahora intentamos con cada fila
            $("#tabla-registros table tbody tr").each(function (index) {
                console.log(`Analizando fila ${index}:`);
                $(this)
                    .find("td")
                    .each(function (colIndex) {
                        console.log(
                            `  Col ${colIndex}: ${$(this).text().trim()}`
                        );
                    });

                // Intentar encontrar la columna de calidad y kilos basado en los encabezados
                let calidadIndex = -1;
                let kilosIndex = -1;

                $("#tabla-registros table thead th").each(function (idx) {
                    const headerText = $(this).text().trim().toLowerCase();
                    if (headerText.includes("calidad")) {
                        calidadIndex = idx;
                    }
                    if (headerText.includes("kilo")) {
                        kilosIndex = idx;
                    }
                });

                if (calidadIndex >= 0 && kilosIndex >= 0) {
                    const calidad = $(this)
                        .find(`td:eq(${calidadIndex})`)
                        .text()
                        .trim()
                        .toLowerCase();
                    const kilosText = $(this)
                        .find(`td:eq(${kilosIndex})`)
                        .text()
                        .trim();
                    const kilos = parseFloat(kilosText.replace(/,/g, "")) || 0;

                    console.log(
                        `Usando índices: Calidad(${calidadIndex})="${calidad}", Kilos(${kilosIndex})=${kilos}`
                    );

                    if (
                        calidad === "premium" ||
                        calidad === "a" ||
                        calidad === "a+" ||
                        calidad.includes("premium") ||
                        calidad.includes("a+")
                    ) {
                        kilosPremium += kilos;
                        console.log(
                            `Sumando ${kilos} kg premium, total: ${kilosPremium}`
                        );
                    }
                }
            });
        }
    }

    console.log("Total kilos premium calculados:", kilosPremium);

    // Actualizar el indicador en el dashboard
    $("#kilosPremium").text(kilosPremium.toFixed(2) + " kg");

    // Calcular y mostrar el porcentaje de premium
    // const totalKilosText = $("#kilosRecepcion").val();
    // console.log("Texto total kilos:", totalKilosText);

    const totalKilos = $("#kilosRecepcion").val() || 0;
    console.log("Total kilos parseado:", totalKilos);

    if (totalKilos > 0) {
        const porcentajePremium = (kilosPremium / totalKilos) * 100;
        $("#porcentajePremium").text(porcentajePremium.toFixed(2) + "%");
        console.log("Porcentaje premium:", porcentajePremium.toFixed(2) + "%");
    } else {
        $("#porcentajePremium").text("0.0%");
        console.log("Porcentaje premium: 0.0% (no hay kilos totales)");
    }
}

$(document).ready(function () {
    // Llamar a la función cuando la página esté lista solo si no es planilla de porciones o ahumado
    const tipoPlanilla = parseInt($("#tipo_planilla").val());
    if (tipoPlanilla !== 2 && tipoPlanilla !== 4) {
        setTimeout(function () {
            calcularKilosPremium();
            console.log("Cálculo inicial de kilos premium completado");
        }, 1000);

        // Actualizar cuando cambia cualquier tabla relevante
        $("#tabla-registros, #totales").on("DOMSubtreeModified", function () {
            calcularKilosPremium();
        });
    }
});
