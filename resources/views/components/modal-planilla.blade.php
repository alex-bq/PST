{{-- Componente reutilizable para modal de planillas --}}
@props([
    'modalId' => 'modalPlanilla',
    'iframeId' => 'iframePlanilla', 
    'framework' => 'bootstrap', // 'bootstrap' o 'tailwind'
    'size' => 'large' // 'normal', 'large', 'fullscreen'
])

@php
    $sizeClasses = [
        'normal' => 'max-width: 70vw; width: 70vw;',
        'large' => 'max-width: 85vw; width: 85vw;',
        'fullscreen' => 'max-width: 95vw; width: 95vw;'
    ];
    
    $modalStyle = $sizeClasses[$size] ?? $sizeClasses['large'];
@endphp

@if($framework === 'bootstrap')
    <!-- Modal Bootstrap -->
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
        <div class="modal-dialog" style="{{ $modalStyle }}">
            <div class="modal-content">
                <div class="modal-body p-1 position-relative">
                    <!-- Botón cerrar flotante -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2"
                        style="z-index: 1000; background-color: rgba(255,255,255,0.9); border-radius: 50%; padding: 0.4rem;"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                    <!-- Iframe para contenido -->
                    <iframe id="{{ $iframeId }}" 
                        style="width:100%;height:85vh;border:none;border-radius:8px;" 
                        frameborder="0">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Modal Tailwind -->
    <div id="{{ $modalId }}" 
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
        style="align-items: center; justify-content: center;">
        <div class="bg-white rounded-lg" style="{{ $modalStyle }} max-height: 85vh; position: relative;">
            <!-- Botón cerrar flotante -->
            <button type="button" onclick="cerrarModalPlanilla('{{ $modalId }}', '{{ $iframeId }}')"
                style="position: absolute; top: 10px; right: 10px; z-index: 1000; background-color: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 18px; color: #666;"
                aria-label="Close">✕</button>
            <!-- Iframe para contenido -->
            <iframe id="{{ $iframeId }}" 
                style="width:100%;height:85vh;border:none;border-radius:8px;" 
                frameborder="0">
            </iframe>
        </div>
    </div>
@endif

@push('scripts')
<script>
    /**
     * Función unificada para abrir modal de planilla
     * @param {string} codPlanilla - ID de la planilla
     * @param {string} modalId - ID del modal (opcional)
     * @param {string} iframeId - ID del iframe (opcional)
     * @param {string} framework - Framework CSS (optional)
     */
    function abrirModalPlanilla(codPlanilla, modalId = '{{ $modalId }}', iframeId = '{{ $iframeId }}', framework = '{{ $framework }}') {
        const url = "{{ url('/ver-planilla/') }}/" + codPlanilla;
        const modal = document.getElementById(modalId);
        const iframe = document.getElementById(iframeId);
        
        if (!modal || !iframe) {
            console.error('Modal o iframe no encontrado:', { modalId, iframeId });
            return;
        }
        
        // Configurar iframe
        iframe.src = url;
        
        // Mostrar modal según framework
        if (framework === 'bootstrap') {
            $('#' + modalId).modal('show');
        } else {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        console.log('Modal abierto:', { codPlanilla, modalId, framework });
    }

    /**
     * Función unificada para cerrar modal de planilla
     * @param {string} modalId - ID del modal
     * @param {string} iframeId - ID del iframe
     */
    function cerrarModalPlanilla(modalId = '{{ $modalId }}', iframeId = '{{ $iframeId }}') {
        const modal = document.getElementById(modalId);
        const iframe = document.getElementById(iframeId);
        
        if (!modal || !iframe) return;
        
        // Limpiar iframe
        iframe.src = '';
        
        // Ocultar modal según framework
        if ('{{ $framework }}' === 'bootstrap') {
            $('#' + modalId).modal('hide');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        console.log('Modal cerrado:', { modalId });
    }

    @if($framework === 'tailwind')
    // Event listener para cerrar modal al hacer clic fuera (solo Tailwind)
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('{{ $modalId }}');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    cerrarModalPlanilla('{{ $modalId }}', '{{ $iframeId }}');
                }
            });
        }
    });
    @endif
    
    // Event listener global para cerrar con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('{{ $modalId }}');
            if (modal && (
                ('{{ $framework }}' === 'bootstrap' && modal.classList.contains('show')) ||
                ('{{ $framework }}' === 'tailwind' && !modal.classList.contains('hidden'))
            )) {
                cerrarModalPlanilla('{{ $modalId }}', '{{ $iframeId }}');
            }
        }
    });
</script>
@endpush 