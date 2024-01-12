<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index()
    {
        $codUsuario = Session::get('user.id');
        $rolUsuario = Session::get('user.rol_admin');

        $condicionConsulta = function ($query) use ($codUsuario, $rolUsuario) {
            if (!$rolUsuario) {
                $query->where(function ($query) use ($codUsuario) {
                    $query->where('cod_planillera', $codUsuario)
                        ->orWhere('cod_supervisor', $codUsuario);
                });
            }
        };

        $planillas = DB::table('v_planilla_pst')
            ->select('*')
            ->where($condicionConsulta)
            ->get();

        // Si $planillas es null o no es una colección, inicializarlo como una colección vacía
        $planillas = $planillas ?? collect();

        return view('index', ['planillas' => $planillas]);
    }
}
