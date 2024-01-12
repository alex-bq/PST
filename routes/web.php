<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanillaController;
use App\Http\Controllers\AuthController;


Route::get("/inicio", [IndexController::class,'index'])->name('inicio');
Route::get('/planilla', [PlanillaController::class, 'mostrarDatos']);
Route::post('/agregar-registro', [PlanillaController::class, 'agregarRegistro']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



