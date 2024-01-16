<?php


use App\Http\Controllers\IndexController;
use App\Http\Controllers\PlanillaController;
use App\Http\Controllers\AuthController;

// Ruta de inicio de sesión
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

// Rutas que requieren autenticación

    Route::get('/inicio', [IndexController::class, 'index'])->name('inicio');
    Route::post('/procesar-formulario', [IndexController::class, 'procesarFormulario'])->name('procesar.formulario');
    Route::get('/planilla/{id}', [PlanillaController::class, 'mostrarPlanilla'])->name('planilla');
    Route::post('/agregar-registro', [PlanillaController::class, 'agregarRegistro'])->name('agregar-registro');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');







