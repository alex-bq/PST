<!-- Modal para Planillas -->
<div id="modal-planillas" class="modal">
    <div class="bg-white rounded-lg max-w-4xl max-h-5/6 overflow-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">📋 Planillas</h3>
            <button onclick="cerrarModal('planillas')" class="text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="h-6 w-6"></i>
            </button>
        </div>
        <div id="contenido-planillas">
            <!-- Contenido se carga dinámicamente -->
            <p class="text-gray-500">Cargando detalles de planillas...</p>
        </div>
    </div>
</div>

<!-- Modal para Productos -->
<div id="modal-productos" class="modal">
    <div class="bg-white rounded-lg max-w-6xl max-h-5/6 overflow-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">📦 Productos</h3>
            <button onclick="cerrarModal('productos')" class="text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="h-6 w-6"></i>
            </button>
        </div>
        <div id="contenido-productos">
            <!-- Contenido se carga dinámicamente -->
            <p class="text-gray-500">Cargando detalles de productos...</p>
        </div>
    </div>
</div>

<!-- Modal para Tiempos Muertos -->
<div id="modal-tiempos" class="modal">
    <div class="bg-white rounded-lg max-w-4xl max-h-5/6 overflow-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">⏱️ Tiempos Muertos</h3>
            <button onclick="cerrarModal('tiempos')" class="text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="h-6 w-6"></i>
            </button>
        </div>
        <div id="contenido-tiempos">
            <!-- Contenido se carga dinámicamente -->
            <p class="text-gray-500">Cargando tiempos muertos...</p>
        </div>
    </div>
</div>

<script>
    // Función para cerrar modales
    function cerrarModal(tipo) {
        const modal = document.getElementById(`modal-${tipo}`);
        modal.classList.remove('active');
    }

    // Cerrar modales al hacer clic fuera
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });
</script>