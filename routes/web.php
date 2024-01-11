<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanillaController;

Route::get("/inicio", [IndexController::class,'index']);
Route::get('/planilla', [PlanillaController::class, 'mostrarDatos']);
Route::post('/agregar-registro', [PlanillaController::class, 'agregarRegistro']);



