/**
 * MODAL PLANILLA GLOBAL - Sistema PST
 * Archivo JavaScript unificado para manejar modales de planillas con iframe
 * Soporta tanto Bootstrap como Tailwind CSS
 */

class ModalPlanilla {
    constructor() {
        this.modalesActivos = new Map();
        this.initEventListeners();
    }

    /**
     * Configuración automática del modal según el framework detectado
     * @param {string} modalId - ID del modal
     * @returns {object} Configuración detectada
     */
    detectarFramework(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return null;

        const esBootstrap =
            modal.classList.contains("modal") ||
            modal.classList.contains("fade");
        const esTailwind =
            modal.classList.contains("fixed") ||
            modal.classList.contains("hidden");

        return {
            framework: esBootstrap
                ? "bootstrap"
                : esTailwind
                ? "tailwind"
                : "unknown",
            modal: modal,
        };
    }

    /**
     * Función principal para abrir modal de planilla
     * @param {string|number} codPlanilla - ID de la planilla
     * @param {object} opciones - Configuración opcional
     */
    abrir(codPlanilla, opciones = {}) {
        const config = {
            modalId: opciones.modalId || this.detectarModalPorDefecto(),
            iframeId: opciones.iframeId || this.detectarIframePorDefecto(),
            baseUrl: opciones.baseUrl || window.baseUrl || "",
            onOpen: opciones.onOpen || null,
            onClose: opciones.onClose || null,
            ...opciones,
        };

        const deteccion = this.detectarFramework(config.modalId);
        if (!deteccion) {
            console.error("❌ Modal no encontrado:", config.modalId);
            return false;
        }

        const modal = deteccion.modal;
        const iframe = document.getElementById(config.iframeId);

        if (!iframe) {
            console.error("❌ Iframe no encontrado:", config.iframeId);
            return false;
        }

        // Construir URL
        const url = `${config.baseUrl}/ver-planilla/${codPlanilla}`;

        // Guardar configuración para este modal
        this.modalesActivos.set(config.modalId, {
            ...config,
            framework: deteccion.framework,
            modal: modal,
            iframe: iframe,
        });

        // Configurar iframe con loading
        this.mostrarCargando(iframe);
        iframe.src = url;

        // Mostrar modal según framework
        this.mostrarModal(config.modalId, deteccion.framework);

        // Callback de apertura
        if (config.onOpen) config.onOpen(codPlanilla, config);

        console.log("✅ Modal abierto:", {
            planilla: codPlanilla,
            modal: config.modalId,
            framework: deteccion.framework,
        });

        return true;
    }

    /**
     * Cerrar modal específico
     * @param {string} modalId - ID del modal a cerrar
     */
    cerrar(modalId = null) {
        // Si no se especifica modalId, buscar modal activo
        if (!modalId) {
            const modalActivo = this.encontrarModalActivo();
            if (!modalActivo) return false;
            modalId = modalActivo;
        }

        const config = this.modalesActivos.get(modalId);
        if (!config) {
            console.warn("⚠️ Modal no está en la lista de activos:", modalId);
            return false;
        }

        // Limpiar iframe
        config.iframe.src = "";
        this.ocultarCargando(config.iframe);

        // Ocultar modal según framework
        this.ocultarModal(modalId, config.framework);

        // Callback de cierre
        if (config.onClose) config.onClose(modalId, config);

        // Limpiar de la lista
        this.modalesActivos.delete(modalId);

        console.log("✅ Modal cerrado:", modalId);
        return true;
    }

    /**
     * Mostrar modal según framework
     */
    mostrarModal(modalId, framework) {
        const modal = document.getElementById(modalId);

        if (framework === "bootstrap") {
            if (window.$ && $.fn.modal) {
                $(`#${modalId}`).modal("show");
            } else {
                modal.classList.add("show");
                modal.style.display = "block";
            }
        } else if (framework === "tailwind") {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }
    }

    /**
     * Ocultar modal según framework
     */
    ocultarModal(modalId, framework) {
        const modal = document.getElementById(modalId);

        if (framework === "bootstrap") {
            if (window.$ && $.fn.modal) {
                $(`#${modalId}`).modal("hide");
            } else {
                modal.classList.remove("show");
                modal.style.display = "none";
            }
        } else if (framework === "tailwind") {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    }

    /**
     * Mostrar indicador de carga en iframe
     */
    mostrarCargando(iframe) {
        const loadingHtml = `
            <div style="display: flex; justify-content: center; align-items: center; height: 100vh; background: #f8f9fa;">
                <div style="text-align: center;">
                    <div style="width: 40px; height: 40px; border: 4px solid #e3e3e3; border-top: 4px solid #007bff; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                    <p style="color: #6c757d; margin: 0;">Cargando planilla...</p>
                </div>
            </div>
            <style>
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            </style>
        `;

        iframe.srcdoc = loadingHtml;
    }

    /**
     * Ocultar indicador de carga
     */
    ocultarCargando(iframe) {
        iframe.srcdoc = "";
    }

    /**
     * Detectar modal por defecto en la página
     */
    detectarModalPorDefecto() {
        const posiblesIds = [
            "verPlanillaModal",
            "modalDetallePlanilla",
            "modalPlanilla",
            "planillaModal",
        ];

        for (const id of posiblesIds) {
            if (document.getElementById(id)) return id;
        }

        return "verPlanillaModal"; // fallback
    }

    /**
     * Detectar iframe por defecto
     */
    detectarIframePorDefecto() {
        const posiblesIds = ["iframePlanilla", "iframePlanillaDetalle"];

        for (const id of posiblesIds) {
            if (document.getElementById(id)) return id;
        }

        return "iframePlanilla"; // fallback
    }

    /**
     * Encontrar modal actualmente visible
     */
    encontrarModalActivo() {
        for (const [modalId, config] of this.modalesActivos) {
            const modal = config.modal;

            if (config.framework === "bootstrap") {
                if (
                    modal.classList.contains("show") ||
                    modal.style.display === "block"
                ) {
                    return modalId;
                }
            } else if (config.framework === "tailwind") {
                if (!modal.classList.contains("hidden")) {
                    return modalId;
                }
            }
        }
        return null;
    }

    /**
     * Inicializar event listeners globales
     */
    initEventListeners() {
        // Escape key para cerrar
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                this.cerrar();
            }
        });

        // Click fuera del modal para cerrar (Tailwind)
        document.addEventListener("click", (e) => {
            for (const [modalId, config] of this.modalesActivos) {
                if (
                    config.framework === "tailwind" &&
                    e.target === config.modal
                ) {
                    this.cerrar(modalId);
                    break;
                }
            }
        });

        console.log("🚀 ModalPlanilla Global inicializado");
    }

    /**
     * Función para compatibilidad con código existente
     */
    static crearFuncionesGlobales() {
        // Crear instancia global
        window.modalPlanillaGlobal = new ModalPlanilla();

        // Funciones de compatibilidad
        window.abrirModal = function (codPlanilla) {
            return window.modalPlanillaGlobal.abrir(codPlanilla, {
                modalId: "verPlanillaModal",
                iframeId: "iframePlanilla",
            });
        };

        window.abrirModalDetallePlanilla = function (codPlanilla) {
            return window.modalPlanillaGlobal.abrir(codPlanilla, {
                modalId: "modalDetallePlanilla",
                iframeId: "iframePlanillaDetalle",
            });
        };

        window.cerrarModalDetallePlanilla = function () {
            return window.modalPlanillaGlobal.cerrar("modalDetallePlanilla");
        };

        window.cerrarModalPlanilla = function (modalId, iframeId) {
            return window.modalPlanillaGlobal.cerrar(modalId);
        };

        console.log("🔗 Funciones de compatibilidad creadas");
    }
}

// Auto-inicialización cuando el DOM esté listo
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        ModalPlanilla.crearFuncionesGlobales();
    });
} else {
    ModalPlanilla.crearFuncionesGlobales();
}
