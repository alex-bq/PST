<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListadoController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('index');
// });
Route::get('/', [ListadoController::class, 'index']);

Route::get('/test-database-connection', function () {
    try {
        $pdo = new PDO(
            "sqlsrv:Server=" . config('database.connections.sqlsrv.host') . "," . config('database.connections.sqlsrv.port') . ";Database=" . config('database.connections.sqlsrv.database'),
            config('database.connections.sqlsrv.username'),
            config('database.connections.sqlsrv.password')
        );

        dd('Conexión exitosa');
    } catch (PDOException $e) {
        dd("Error de conexión: " . $e->getMessage());
    }
});
