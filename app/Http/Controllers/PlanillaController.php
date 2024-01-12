<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PlanillaController extends Controller
{

    public function mostrarPlanilla($idPlanilla)

    
{
    $cortes = DB::select('SELECT dbo.corte.cod_corte,dbo.corte.nombre FROM dbo.corte WHERE dbo.corte.inactivo = 0 AND dbo.corte.transito = 1 GROUP BY dbo.corte.nombre,dbo.corte.cod_corte ORDER BY dbo.corte.nombre ASC;');
    $procesos = DB::select('SELECT dbo.subproceso.cod_sproceso,dbo.subproceso.nombre FROM dbo.subproceso WHERE dbo.subproceso.inactivo = 0 ORDER BY dbo.subproceso.nombre ASC;');
    $calibres = DB::select('SELECT dbo.calibre.cod_calib, dbo.calibre.nombre FROM dbo.calibre WHERE inactivo = 0 AND transito = 1 ;');
    $calidades = DB::select('SELECT DISTINCT MIN(dbo.calidad.cod_cald) as cod_cald,dbo.calidad.nombre FROM dbo.calidad WHERE inactivo = 0 GROUP BY dbo.calidad.nombre ORDER BY dbo.calidad.nombre ASC;');

    $idUsuario = session('user')['id'];
    $rolUsuario = session('user')['rol_admin'];


    $desc_planilla = DB::table('v_planilla_pst')
            ->select('*')
            ->where('cod_planilla',$idPlanilla)
            ->first();

    if ($rolUsuario) {
        // Si es admin, muestra la planilla seleccionada
        $planilla = DB::table("dbo.v_registro_planilla_pst")
            ->select('cInicial', 'cFinal', 'proceso', 'calibre', 'calidad', 'piezas', 'kilos')
            ->get();
    } else {
        // Si no es admin, verifica que la planilla pertenezca al usuario
        $planilla = DB::table("dbo.v_registro_planilla_pst")
            ->select('cInicial', 'cFinal', 'proceso', 'calibre', 'calidad', 'piezas', 'kilos')
            ->where('cod_planilla', $idPlanilla)
            ->get();

        
    }

    

    return view('planilla', compact('planilla', 'cortes', 'procesos', 'calibres', 'calidades', 'idPlanilla','desc_planilla'));
}

    
    public function agregarRegistro(Request $request)
    {
        $idPlanilla = $request->input('idPlanilla');
        $codCorteIni = $request->input('cInicial');
        $codCorteFin = $request->input('cFinal');   
        $codProceso = $request->input('proceso');       
        $codCalibre = $request->input('calibre');        
        $codCalidad = $request->input('calidad');        
        $piezas = $request->input('piezas');
        $kilos = $request->input('kilos');


        DB::table('registro_planilla_pst')->insert([
            'cod_planilla' => $idPlanilla ,
            'cod_corte_ini' => $codCorteIni,
            'cod_corte_fin' => $codCorteFin,
            'cod_proceso' => $codProceso,
            'cod_calibre' => $codCalibre,
            'cod_calidad' => $codCalidad,
            'piezas' => $piezas,
            'kilos' => $kilos,
            'guardado' => 0, 
        ]);

        
        $planillaActualizada = DB::table("dbo.v_registro_planilla_pst")
            ->where('cod_planilla', $idPlanilla)
            ->select('cInicial', 'cFinal', 'proceso', 'calibre', 'calidad', 'piezas', 'kilos')
            ->get();

        return response()->json(['success' => true, 'mensaje' => 'Registro agregado exitosamente', 'planilla' => $planillaActualizada]);

    }
}
    

