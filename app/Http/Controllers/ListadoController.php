<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ListadoController extends Controller
{
    public function index()
    {
        // Obtener datos para los select
        $cortes = DB::select('SELECT dbo.corte.cod_corte,dbo.corte.nombre FROM dbo.corte WHERE dbo.corte.inactivo = 0 AND dbo.corte.transito = 1 GROUP BY dbo.corte.nombre,dbo.corte.cod_corte,dbo.corte.codigo ORDER BY dbo.corte.nombre ASC;');
        $procesos = DB::select('SELECT dbo.subproceso.cod_sproceso,dbo.subproceso.nombre FROM dbo.subproceso WHERE dbo.subproceso.inactivo = 0 ORDER BY dbo.subproceso.nombre ASC;');
        $calibres = DB::select('SELECT dbo.calibre.cod_calib, dbo.calibre.nombre FROM dbo.calibre WHERE inactivo = 0 AND transito = 1;');
        $calidades = DB::select('SELECT DISTINCT MIN(dbo.calidad.cod_cald) as cod_cald,dbo.calidad.nombre FROM dbo.calidad WHERE inactivo = 0 GROUP BY dbo.calidad.nombre ORDER BY dbo.calidad.nombre ASC;');

        // Obtener datos principales
        $listado = DB::table("dbo.registro_planilla_pst")
            ->select('cod_reg', 'cod_corte_ini', 'cod_corte_fin', 'cod_proceso', 'cod_calibre', 'cod_calidad', 'piezas', 'kilos')
            ->get();

        return view('index', compact('listado', 'cortes', 'procesos', 'calibres', 'calidades'));
        }
    
}
