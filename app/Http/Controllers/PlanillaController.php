<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PlanillaController extends Controller
{

    public function mostrarPlanilla($idPlanilla)
    {

        if (!session('user')) {
            return redirect('/login');
        }

        $cortes = DB::select('SELECT cod_corte,nombre FROM pst.dbo.corte WHERE inactivo = 0 AND transito = 1 GROUP BY nombre,cod_corte ORDER BY nombre ASC;');
        $procesos = DB::select('SELECT cod_sproceso,nombre FROM pst.dbo.subproceso WHERE inactivo = 0 ORDER BY nombre ASC;');
        $calibres = DB::select('SELECT cod_calib,nombre FROM calibre WHERE inactivo = 0 AND transito = 1 ;');
        $calidades = DB::select('SELECT cod_cald,nombre FROM pst.dbo.calidad WHERE inactivo = 0 ORDER BY nombre ASC;');

        $empresas = DB::select('SELECT cod_empresa,descripcion FROM bdsystem.dbo.empresas WHERE inactivo=0 ORDER BY descripcion ASC;');
        $proveedores = DB::select('SELECT cod_proveedor,descripcion FROM bdsystem.dbo.proveedores WHERE inactivo=0 ORDER BY descripcion ASC;');
        $especies = DB::select('SELECT cod_especie,descripcion FROM bdsystem.dbo.especies WHERE inactivo=0 ORDER BY descripcion ASC;');
        $turnos = DB::select('SELECT codTurno,NomTurno FROM bdsystem.dbo.turno WHERE inactivo=0 ORDER BY NomTurno ASC;');
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=2 ORDER BY nombre ASC;');
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=1 ORDER BY nombre ASC;');




        $desc_planilla = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->first();

        if (!$desc_planilla) {
            // La planilla no existe, redirigir al usuario a la página de inicio
            return redirect('/inicio')->with('error', 'La planilla no existe.');
        }


        $planilla = DB::table("pst.dbo.v_registro_planilla_pst")
            ->select('cInicial', 'cFinal', 'proceso', 'calibre', 'calidad', 'piezas', 'kilos')
            ->where('cod_planilla', $idPlanilla)
            ->get();






        return view('planilla', compact('planilla', 'cortes', 'procesos', 'calibres', 'calidades', 'idPlanilla', 'desc_planilla', 'empresas', 'proveedores', 'especies', 'turnos', 'supervisores', 'planilleros'));
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


        DB::table('pst.dbo.registro_planilla_pst')->insert([
            'cod_planilla' => $idPlanilla,
            'cod_corte_ini' => $codCorteIni,
            'cod_corte_fin' => $codCorteFin,
            'cod_proceso' => $codProceso,
            'cod_calibre' => $codCalibre,
            'cod_calidad' => $codCalidad,
            'piezas' => $piezas,
            'kilos' => $kilos,
            'guardado' => 0,
        ]);


        $planillaActualizada = DB::table("pst.dbo.v_registro_planilla_pst")
            ->where('cod_planilla', $idPlanilla)
            ->select('cInicial', 'cFinal', 'proceso', 'calibre', 'calidad', 'piezas', 'kilos')
            ->get();

        return response()->json(['success' => true, 'mensaje' => 'Registro agregado exitosamente', 'planilla' => $planillaActualizada]);

    }
}


