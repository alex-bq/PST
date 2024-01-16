<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index()
    {
        $empresas = DB::select('SELECT cod_empresa,descripcion FROM bdsystem.dbo.empresas WHERE inactivo=0 ORDER BY descripcion ASC;');
        $especies = DB::select('SELECT cod_especie,descripcion FROM bdsystem.dbo.especies WHERE inactivo=0 ORDER BY descripcion ASC;');
        $turnos= DB::select('SELECT codTurno,NomTurno FROM bdsystem.dbo.turno WHERE inactivo=0 ORDER BY NomTurno ASC;');
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=2 ORDER BY nombre ASC;');
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=1 ORDER BY nombre ASC;');


        $planillas = DB::table('pst.dbo.v_planilla_pst')
        ->select('*')
        ->get();


        // Si $planillas es null o no es una colección, inicializarlo como una colección vacía

        $planillas = $planillas ?? collect();

        return view('index',compact('empresas','especies','turnos','supervisores','planilleros','planillas'));
    }

    public function procesarFormulario(Request $request)
    {
        // Validar el formulario, asegúrate de tener reglas de validación adecuadas
        $request->validate([
            'codLote' => 'required',
            'empresa' => 'required',
            'especie' => 'required',
            'fechaTurno' => 'required',
            'turno' => 'required',
            'supervisor' => 'required',
            'planillero' => 'required',
        ]);

        // Verificar si el lote existe
        $loteExistente = DB::table('bdsystem.dbo.lotes')
            ->where('nombre', $request->input('codLote'))
            ->exists();

        if (!$loteExistente) {
            // El lote no existe, retornar un mensaje de error
            return response()->json(['error' => 'El lote no existe.'], 422);
        }

        // Obtener el ID del lote
        $idLote = DB::table('bdsystem.dbo.lotes')
            ->where('nombre', $request->input('codLote'))
            ->value('cod_lote');

        try {
            // Intentar insertar la planilla
            $idPlanilla = DB::table('pst.dbo.planilla_pst')->insertGetId([
                'cod_lote' => $idLote,
                'fec_turno' => $request->input('fechaTurno'),
                'cod_turno' => $request->input('turno'),
                'cod_empresa' => $request->input('empresa'),
                'cod_especie' => $request->input('especie'),
                'cod_planillero' => $request->input('planillero'),
                'cod_supervisor' => $request->input('supervisor'),
                'guardado' => 0,
            ]);

            // Redirigir a la página principal con un mensaje de éxito
            return redirect('/planilla/'.$idPlanilla)->with('success', 'La planilla se creó correctamente.');
        } catch (\Exception $e) {
            // Manejar el error, retornar un mensaje de error
            dd($e->getMessage());
            return response()->json(['error' => 'Error al crear la planilla.'], 422);
        }
    }

    
}
