<?php


use App\Http\Controllers\IndexController;
use App\Http\Controllers\PlanillaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\adminController;

// Ruta de inicio de sesión
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/plantilla', [IndexController::class, 'plantilla'])->name('plantilla');


// Rutas que requieren autenticación

Route::get('/inicio', [IndexController::class, 'index'])->name('inicio');
Route::post('/obtener-valores-lote', [IndexController::class, 'obtenerValores'])->name('obtener_valores_lote');
Route::post('/filtrar-tabla', [IndexController::class, 'filtrarTabla'])->name('filtrar.tabla');
Route::post('/filtrar-lotes-en-tiempo-real', [IndexController::class, 'filtrarLotesEnTiempoReal'])->name('filtrar_lotes_en_tiempo_real');
Route::post('/procesar-formulario', [IndexController::class, 'procesarFormulario'])->name('procesar.formulario');

Route::get('/obtener-datos-fila/{id}', [PlanillaController::class, 'obtenerDatosFila'])->name('obtenerDatosFila');
Route::get('/planilla/{id}', [PlanillaController::class, 'mostrarPlanilla'])->name('planilla');
Route::post('/guardar-planilla', [PlanillaController::class, 'guardarPlanilla'])->name('guardar');
Route::post('/modificar-planilla/{id}', [PlanillaController::class, 'modificarPlanilla'])->name('modificar-planilla');
Route::post('/modificar-registro', [PlanillaController::class, 'editarRegistro'])->name('editarRegistro');
Route::post('/eliminar-registro', [PlanillaController::class, 'eliminarRegistro'])->name('eliminarRegistro');
Route::post('/agregar-registro', [PlanillaController::class, 'agregarRegistro'])->name('agregar-registro');

Route::get('/ver-planilla/{id}', [PlanillaController::class, 'verPlanilla'])->name('verPlanilla');

Route::get('/admin', [adminController::class, 'admin'])->name('admin');

Route::get('/mantenedor-corte', [adminController::class, 'mCorte'])->name('mCorte');

Route::post('/guardar-corte', [adminController::class, 'guardarCorte'])->name('guardarCorte');
Route::post('/editar-corte', [adminController::class, 'editarCorte'])->name('editarCorte');
Route::post('/eliminar-corte', [adminController::class, 'eliminarCorte'])->name('eliminarCorte');

Route::get('/mantenedor-calidad', [adminController::class, 'mCalidad'])->name('mCalidad');

Route::post('/guardar-calidad', [adminController::class, 'guardarCalidad'])->name('guardarCalidad');
Route::post('/editar-calidad', [adminController::class, 'editarCalidad'])->name('editarCalidad');
Route::post('/eliminar-calidad', [adminController::class, 'eliminarCalidad'])->name('eliminarCalidad');

Route::get('/mantenedor-destino', [adminController::class, 'mDestino'])->name('mDestino');

Route::post('/guardar-destino', [adminController::class, 'guardarDestino'])->name('guardarDestino');
Route::post('/editar-destino', [adminController::class, 'editarDestino'])->name('editarDestino');
Route::post('/eliminar-destino', [adminController::class, 'eliminarDestino'])->name('eliminarDestino');

Route::get('/mantenedor-calibre', [adminController::class, 'mCalibre'])->name('mCalibre');

Route::post('/guardar-calibre', [adminController::class, 'guardarCalibre'])->name('guardarCalibre');
Route::post('/editar-calibre', [adminController::class, 'editarCalibre'])->name('editarCalibre');
Route::post('/eliminar-calibre', [adminController::class, 'eliminarCalibre'])->name('eliminarCalibre');

Route::get('/mantenedor-sala', [adminController::class, 'mSala'])->name('mSala');

Route::post('/guardar-sala', [adminController::class, 'guardarSala'])->name('guardarSala');
Route::post('/editar-sala', [adminController::class, 'editarSala'])->name('editarSala');
Route::post('/eliminar-sala', [adminController::class, 'eliminarSala'])->name('eliminarSala');

Route::get('/mantenedor-usuario', [adminController::class, 'mUsuario'])->name('mUsuario');

Route::post('/guardar-usuario', [adminController::class, 'guardarUsuario'])->name('guardarUsuario');
Route::post('/editar-usuario', [adminController::class, 'editarUsuario'])->name('editarUsuario');
Route::post('/eliminar-usuario', [adminController::class, 'eliminarUsuario'])->name('eliminarUsuario');


Route::get('/logout', [AuthController::class, 'logout'])->name('logout');







