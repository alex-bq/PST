<?php


use App\Http\Controllers\IndexController;
use App\Http\Controllers\PlanillaController;
use App\Http\Controllers\AuthController;

// Ruta de inicio de sesión
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

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

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');







