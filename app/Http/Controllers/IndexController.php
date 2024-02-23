<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class IndexController extends Controller
{

    public function iframe()
    {
        if (!session('user')) {
            return redirect('/login');
        }

        return view('layouts.main-iframe');
    }
    public function main()
    {
        if (!session('user')) {
            return redirect('/login');
        }
        return view('layouts.main');
    }
    public function index()
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $empresas = DB::select('SELECT cod_empresa,descripcion FROM bdsystem.dbo.empresas WHERE inactivo=0 ORDER BY descripcion ASC;');
        $procesos = DB::select('SELECT cod_sproceso,UPPER(nombre) as nombre FROM bdsystem.dbo.subproceso WHERE inactivo=0 ORDER BY nombre ASC;');
        $proveedores = DB::select('SELECT cod_proveedor,descripcion FROM bdsystem.dbo.proveedores WHERE inactivo=0 ORDER BY descripcion ASC;');
        $especies = DB::select('SELECT cod_especie,descripcion FROM bdsystem.dbo.especies WHERE inactivo=0 ORDER BY descripcion ASC;');
        $turnos = DB::select('SELECT codTurno,NomTurno FROM bdsystem.dbo.turno WHERE inactivo=0 ORDER BY NomTurno ASC;');
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=2 AND activo = 1 ORDER BY nombre ASC;');
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=1 AND activo = 1 ORDER BY nombre ASC;');


        $fechaHoy = Carbon::now()->format('Y-m-d');
        $fechaHace7Dias = Carbon::now()->subDays(7)->format('Y-m-d');

        $planillas7dias = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('guardado', 1);


        if (session('user.cod_rol') != 3) {
            $planillas7dias->where(function ($query) {
                $query->where('cod_planillero', session('user.cod_usuario'))
                    ->orWhere('cod_supervisor', session('user.cod_usuario'));
            });
        }

        $planillas7dias = $planillas7dias->whereBetween('fec_turno', [$fechaHace7Dias, $fechaHoy])
            ->orderByDesc('fec_turno')
            ->get();



        $planillasHoy = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('guardado', 1);

        if (session('user.cod_rol') != 3) {
            $planillasHoy->where(function ($query) {
                $query->where('cod_planillero', session('user.cod_usuario'))
                    ->orWhere('cod_supervisor', session('user.cod_usuario'));
            });
        }

        $planillasHoy = $planillasHoy->whereDate('fec_turno', $fechaHoy)
            ->orderByDesc('fec_turno')
            ->get();

        $noGuardado = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('guardado', 0)
            ->where('cod_usuario_crea', session('user.cod_usuario'))
            ->orderByDesc('fec_turno')
            ->get();




        return view('index', compact('procesos', 'empresas', 'proveedores', 'especies', 'turnos', 'supervisores', 'planilleros', 'planillasHoy', 'planillas7dias', 'noGuardado'));
    }

    public function planillas()
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $empresas = DB::select('SELECT cod_empresa,descripcion FROM bdsystem.dbo.empresas WHERE inactivo=0 ORDER BY descripcion ASC;');
        $procesos = DB::select('SELECT cod_sproceso,UPPER(nombre) as nombre FROM bdsystem.dbo.subproceso WHERE inactivo=0 ORDER BY nombre ASC;');
        $proveedores = DB::select('SELECT cod_proveedor,descripcion FROM bdsystem.dbo.proveedores WHERE inactivo=0 ORDER BY descripcion ASC;');
        $especies = DB::select('SELECT cod_especie,descripcion FROM bdsystem.dbo.especies WHERE inactivo=0 ORDER BY descripcion ASC;');
        $turnos = DB::select('SELECT codTurno,NomTurno FROM bdsystem.dbo.turno WHERE inactivo=0 ORDER BY NomTurno ASC;');
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=2 AND activo = 1 ORDER BY nombre ASC;');
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=1 AND activo = 1 ORDER BY nombre ASC;');

        $planillas = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('guardado', 1)
            ->orderByDesc('fec_turno');



        $planillas = $planillas->get();

        return view('admin.mantencion.planillas', compact('procesos', 'empresas', 'proveedores', 'especies', 'turnos', 'supervisores', 'planilleros', 'planillas'));
    }
    public function eliminarPlanilla($idPlanilla)
    {
        DB::table('pst.dbo.registro_planilla_pst')->where('cod_planilla', $idPlanilla)->delete();

        DB::table('pst.dbo.detalle_planilla_pst')->where('cod_planilla', $idPlanilla)->delete();

        DB::table('pst.dbo.planillas_pst')->where('cod_planilla', $idPlanilla)->delete();

        return response()->json(['message' => 'Planilla eliminada exitosamente']);
    }



    public function filtrarTabla(Request $request)
    {
        $planillas = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('guardado', 1)
            ->orderByDesc('fec_turno');

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
        if ($filtroPlanillero = request('filtroPlanillero')) {
            $planillas->where('cod_planillero', $filtroPlanillero);
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
            ->select('cod_empresa', 'bdsystem.dbo.detalle_lote.cod_proveedor', 'cod_especie', 'cod_sproceso')
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
            'proceso' => 'required',
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
            DB::table('pst.dbo.planillas_pst')->insert([
                'cod_lote' => $idLote,
                'fec_turno' => $request->input('fechaTurno'),
                'cod_turno' => $request->input('turno'),
                'cod_empresa' => $request->input('empresa'),
                'cod_proveedor' => $request->input('proveedor'),
                'cod_especie' => $request->input('especie'),
                'cod_proceso' => $request->input('proceso'),
                'cod_planillero' => $request->input('planillero'),
                'cod_supervisor' => $request->input('supervisor'),
                'cod_usuario_crea_planilla' => session('user.cod_usuario'),
                'guardado' => 0,
            ]);
            $idPlanilla = DB::table('pst.dbo.planillas_pst')
                ->orderBy('cod_planilla', 'desc')
                ->value('cod_planilla');


            return response()->json(['planilla' => $idPlanilla, 'message' => 'La planilla se creó correctamente.']);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => 'Error al crear la planilla.'], 422);
        }
    }
    public function filtrarLotesEnTiempoReal(Request $request)
    {
        $filtroLote = $request->input('filtroLote');

        $query = DB::table('pst.dbo.v_planilla_pst')->select('*')->orderByDesc('fec_turno');


        if (!empty($filtroLote)) {
            $query->where('lote', 'LIKE', '%' . $filtroLote . '%');
        }

        $resultados = $query->get();

        return response()->json(['planillas' => $resultados]);
    }

}
