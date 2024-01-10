<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListadoController;



Route::get('/', [ListadoController::class, 'index'])->name('index');



