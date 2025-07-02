<?php


use App\Http\Controllers\IndexController;
use App\Http\Controllers\PlanillaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\MisInformesController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Ruta de inicio de sesión
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/cambiar-contra', [AuthController::class, 'showContraForm'])->name('cambiarContra');
Route::post('/cambiar-contra', [AuthController::class, 'cambiarContra']);

Route::get('/main-iframe', [IndexController::class, 'iframe'])->name('mainIframe');
Route::get('/main', [IndexController::class, 'main'])->name('main');


// Rutas que requieren autenticación

Route::get('/inicio', [IndexController::class, 'index'])->name('inicio');
Route::get('/planillas', [IndexController::class, 'planillas'])->name('planillas');
Route::post('/obtener-valores-lote', [IndexController::class, 'obtenerValores'])->name('obtener_valores_lote');
Route::post('/filtrar-tabla', [IndexController::class, 'filtrarTabla'])->name('filtrar.tabla');
Route::post('/filtrar-lotes-en-tiempo-real', [IndexController::class, 'filtrarLotesEnTiempoReal'])->name('filtrar_lotes_en_tiempo_real');
Route::post('/procesar-formulario', [IndexController::class, 'procesarFormulario'])->name('procesar.formulario');

Route::get('/obtener-datos-fila/{id}', [PlanillaController::class, 'obtenerDatosFila'])->name('obtenerDatosFila');
Route::get('/planilla/{id}', [PlanillaController::class, 'mostrarPlanilla'])->name('planilla');
Route::post('/guardar-planilla', [PlanillaController::class, 'guardarPlanilla'])->name('guardar');
Route::post('/modificar-planilla/{id}', [PlanillaController::class, 'modificarPlanilla'])->name('modificar-planilla');
Route::post('/eliminar-planilla/{id}', [IndexController::class, 'eliminarPlanilla'])->name('eliminar-planilla');
Route::post('/modificar-registro', [PlanillaController::class, 'editarRegistro'])->name('editarRegistro');
Route::post('/eliminar-registro', [PlanillaController::class, 'eliminarRegistro'])->name('eliminarRegistro');
Route::post('/agregar-registro', [PlanillaController::class, 'agregarRegistro'])->name('agregar-registro');

Route::get('/ver-planilla/{id}', [PlanillaController::class, 'verPlanilla'])->name('verPlanilla');
Route::get('/descargar-planilla/{id}', [PlanillaController::class, 'descargarPlanilla'])->name('descargarPlanilla');

Route::post('/guardar-tiempo-muerto', [PlanillaController::class, 'guardarTiempoMuerto'])->name('guardarTiempoMuerto');

Route::post('/actualizar-producto-objetivo', [PlanillaController::class, 'actualizarProductoObjetivo'])->name('actualizar.producto.objetivo');

Route::get('/mantenedor-corte', [adminController::class, 'mCorte'])->name('mCorte');

Route::post('/guardar-corte', [adminController::class, 'guardarCorte'])->name('guardarCorte');
Route::post('/editar-corte', [adminController::class, 'editarCorte'])->name('editarCorte');

Route::get('/mantenedor-calidad', [adminController::class, 'mCalidad'])->name('mCalidad');

Route::post('/guardar-calidad', [adminController::class, 'guardarCalidad'])->name('guardarCalidad');
Route::post('/editar-calidad', [adminController::class, 'editarCalidad'])->name('editarCalidad');

Route::get('/mantenedor-destino', [adminController::class, 'mDestino'])->name('mDestino');

Route::post('/guardar-destino', [adminController::class, 'guardarDestino'])->name('guardarDestino');
Route::post('/editar-destino', [adminController::class, 'editarDestino'])->name('editarDestino');

Route::get('/mantenedor-calibre', [adminController::class, 'mCalibre'])->name('mCalibre');

Route::post('/guardar-calibre', [adminController::class, 'guardarCalibre'])->name('guardarCalibre');
Route::post('/editar-calibre', [adminController::class, 'editarCalibre'])->name('editarCalibre');

Route::get('/mantenedor-sala', [adminController::class, 'mSala'])->name('mSala');

Route::post('/guardar-sala', [adminController::class, 'guardarSala'])->name('guardarSala');
Route::post('/editar-sala', [adminController::class, 'editarSala'])->name('editarSala');

Route::get('/mantenedor-usuario', [adminController::class, 'mUsuario'])->name('mUsuario');

Route::post('/guardar-usuario', [adminController::class, 'guardarUsuario'])->name('guardarUsuario');
Route::post('/editar-usuario', [adminController::class, 'editarUsuario'])->name('editarUsuario');

Route::get('/obtener-tiempos-muertos/{idPlanilla}', [PlanillaController::class, 'obtenerTiemposMuertos']);
Route::get('/obtener-departamentos', [PlanillaController::class, 'obtenerDepartamentos'])->name('obtener.departamentos');

Route::delete('/eliminar-tiempo-muerto/{id}', [PlanillaController::class, 'eliminarTiempoMuerto']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/informes', [InformeController::class, 'index'])->name('informes');

// Ruta simple para visualizar el detalle de turno
Route::get('/detalle-turno', function () {
    return view('detalle-turno');
})->name('detalle.turno');

Route::get('/informes-diarios/{fecha}', [InformeController::class, 'getInformesDiarios'])->name('informes.diarios');

Route::get('/informes/crear/{fecha}/{turno}', [InformeController::class, 'getDetalleTurno'])
    ->name('informes.crear');

Route::get('/mis-informes', [MisInformesController::class, 'index'])
    ->name('mis-informes');

// Ruta para guardar el informe
Route::post('/informes/store', [InformeController::class, 'store'])->name('informes.store');

// Ruta para ver la lista de informes (para la redirección después de guardar)

Route::post('/informes/validar', [InformeController::class, 'validarInforme'])->name('informes.validar');

Route::delete('informes/{cod_informe}', [MisInformesController::class, 'destroy'])->name('informes.destroy');

Route::get('/informes/search', [MisInformesController::class, 'search'])->name('informes.search');

// Agregar esta nueva ruta
Route::get('/informes/detalle/{fecha}/{turno}', [InformeController::class, 'show'])
    ->name('informes.show');

// ===== NUEVAS RUTAS PARA SISTEMA DE BORRADOR AUTOMÁTICO =====

// Crear borrador automáticamente (reemplaza el enlace anterior de "Crear Informe")
Route::get('/informes/borrador/{fecha}/{turno}', [InformeController::class, 'crearBorrador'])
    ->name('informes.crearBorrador');

// Vista de edición del informe (borrador o completado)
Route::get('/informes/editar/{cod_informe}', [InformeController::class, 'editar'])
    ->name('informes.editar');

// ===== RUTAS AJAX PARA AUTO-GUARDADO =====

// Actualizar comentario de sala (auto-guardado)
Route::post('/informes/comentario/actualizar', [InformeController::class, 'actualizarComentario'])
    ->name('informes.actualizarComentario');

// Subir foto
Route::post('/informes/foto/subir', [InformeController::class, 'subirFoto'])
    ->name('informes.subirFoto');

// Eliminar foto
Route::delete('/informes/foto/eliminar', [InformeController::class, 'eliminarFoto'])
    ->name('informes.eliminarFoto');

// Finalizar informe (cambiar de borrador a completado)
Route::post('/informes/finalizar', [InformeController::class, 'finalizar'])
    ->name('informes.finalizar');

// Obtener jefes de turno para filtro de búsqueda
Route::get('/api/jefes-turno', [MisInformesController::class, 'getJefesTurno'])
    ->name('api.jefesTurno');

Route::get('/dashboard', function () {
    return view('admin.dashboard-productividad');
})->name('dashboard-productividad');

Route::get('/api/dashboard-data', [DashboardController::class, 'getData']);










