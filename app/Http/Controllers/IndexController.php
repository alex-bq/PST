<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index()
    {
        
        $planillas = DB::table('pst.dbo.v_planilla_pst')
        ->select('*')
        ->get();

        // $codUsuario = Session::get('user.cod_usuario');
        // $codRol = Session::get('user.cod_rol');

        // if ($codRol == '3'){

        //     $planillas = DB::table('v_planilla_pst')
        //     ->select('*')
        //     ->get();
        // }
        // else{
        //     $planillas = DB::table('v_planilla_pst')
        //     ->select('*')
        //     ->where('cod_planillera', $codUsuario)
        //     ->orWhere('cod_supervisor', $codUsuario)
        //     ->get();
        // }

        // Si $planillas es null o no es una colección, inicializarlo como una colección vacía
        $planillas = $planillas ?? collect();

        return view('index', ['planillas' => $planillas]);
    }
}
