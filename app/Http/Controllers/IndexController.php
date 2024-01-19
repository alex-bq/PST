<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $empresas = DB::select('SELECT cod_empresa,descripcion FROM bdsystem.dbo.empresas WHERE inactivo=0 ORDER BY descripcion ASC;');
        $proveedores = DB::select('SELECT cod_proveedor,descripcion FROM bdsystem.dbo.proveedores WHERE inactivo=0 ORDER BY descripcion ASC;');
        $especies = DB::select('SELECT cod_especie,descripcion FROM bdsystem.dbo.especies WHERE inactivo=0 ORDER BY descripcion ASC;');
        $turnos = DB::select('SELECT codTurno,NomTurno FROM bdsystem.dbo.turno WHERE inactivo=0 ORDER BY NomTurno ASC;');
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=2 ORDER BY nombre ASC;');
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=1 ORDER BY nombre ASC;');

        $planillas = DB::table('pst.dbo.v_planilla_pst')
            ->select('*');

        // Aplicar filtros si existen
        
        // Agrega más condiciones para otros filtros

        $planillas = $planillas->get() ?? collect();

        return view('index', compact('empresas', 'proveedores', 'especies', 'turnos', 'supervisores', 'planilleros', 'planillas'));
    }


    public function filtrarTabla(Request $request)
{
    $planillas = DB::table('pst.dbo.v_planilla_pst')
        ->select('*');

    // Aplicar filtros si existen
    if ($filtroLote = $request->input('filtroLote')) {
        $planillas->where('lote', 'LIKE', '%' . $filtroLote . '%');
    }
    if ($filtroFecha = request('filtroFecha')) {
        $planillas->whereDate('fec_turno', $filtroFecha);
    }

    if ($filtroTurno = request('filtroTurno')) {
        $planillas->where('turno', $filtroTurno);
    }

    if ($filtroProveedor = request('filtroProv')) {
        $planillas->where('proveedor', $filtroProveedor);
    }

    if ($filtroEmpresa = request('filtroEmpresa')) {
        $planillas->where('empresa', $filtroEmpresa);
    }

    if ($filtroEspecie = request('filtroEspecie')) {
        $planillas->where('especie', $filtroEspecie);
    }
    if ($filtroSupervisor = request('filtroSupervisor')) {
        $planillas->where('cod_supervisor', $filtroSupervisor);
    }

   

    $planillas = $planillas->get() ?? collect();

    return response()->json(['planillas' => $planillas]);
}

    public function obtenerValores(Request $request)
    {
        $loteValue = $request->input('lote');

        $result = DB::table('bdsystem.dbo.lotes')
            ->leftJoin('bdsystem.dbo.detalle_lote', 'bdsystem.dbo.lotes.cod_lote', '=', 'bdsystem.dbo.detalle_lote.cod_lote')
            ->where('nombre', $loteValue)
            ->select('cod_empresa', 'bdsystem.dbo.detalle_lote.cod_proveedor', 'cod_especie')
            ->first();

        if (!$result) {
            // Muestra un mensaje en el terminal

            return response()->json(['error' => 'El lote no existe.'], 422, $result);
        }



        return response()->json($result);
    }

    public function procesarFormulario(Request $request)
    {
        $request->validate([
            'codLote' => 'required',
            'empresa' => 'required',
            'proveedor' => 'required',
            'especie' => 'required',
            'fechaTurno' => 'required',
            'turno' => 'required',
            'supervisor' => 'required',
            'planillero' => 'required',
        ]);

        $loteExistente = DB::table('bdsystem.dbo.lotes')
            ->where('nombre', $request->input('codLote'))
            ->exists();

        if (!$loteExistente) {
            return response()->json(['error' => 'El lote no existe.'], 422);
        }

        $idLote = DB::table('bdsystem.dbo.lotes')
            ->where('nombre', $request->input('codLote'))
            ->value('cod_lote');

        try {
            $idPlanilla = DB::table('pst.dbo.planillas_pst')->insertGetId([
                'cod_lote' => $idLote,
                'fec_turno' => $request->input('fechaTurno'),
                'cod_turno' => $request->input('turno'),
                'cod_empresa' => $request->input('empresa'),
                'cod_proveedor' => $request->input('proveedor'),
                'cod_especie' => $request->input('especie'),
                'cod_planillero' => $request->input('planillero'),
                'cod_supervisor' => $request->input('supervisor'),
                'guardado' => 0,
            ]);

            return response()->json(['redirect' => '/planilla/' . $idPlanilla, 'message' => 'La planilla se creó correctamente.']);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => 'Error al crear la planilla.'], 422);
        }
    }
    public function filtrarLotesEnTiempoReal(Request $request)
{
    $filtroLote = $request->input('filtroLote');

    // Realiza la lógica de filtrado aquí y obtén los resultados
    $resultados = DB::table('pst.dbo.v_planilla_pst')->where('lote', 'LIKE', '%' . $filtroLote . '%')->get();

    // Devuelve los resultados en formato JSON
    return response()->json(['planillas' => $resultados]);
}

}
