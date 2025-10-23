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

        // Obtener datos únicos desde lomar_prod (sin duplicados)
        $empresas = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->select('empresa as descripcion')
            ->distinct()
            ->orderBy('empresa')
            ->get()
            ->map(function ($item) {
                return (object) ['descripcion' => $item->descripcion];
            });

        $procesos = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->select('proceso as nombre')
            ->distinct()
            ->orderBy('proceso')
            ->get()
            ->map(function ($item) {
                return (object) ['nombre' => strtoupper($item->nombre)];
            });

        $proveedores = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->select('proveedor as descripcion')
            ->distinct()
            ->orderBy('proveedor')
            ->get()
            ->map(function ($item) {
                return (object) ['descripcion' => $item->descripcion];
            });

        $especies = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->select('especie as descripcion')
            ->distinct()
            ->orderBy('especie')
            ->get()
            ->map(function ($item) {
                return (object) ['descripcion' => $item->descripcion];
            });
        $turnos = DB::select('SELECT id,nombre FROM [administracion].[dbo].[tipos_turno] WHERE activo=1 ORDER BY id ASC;');
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=2 AND activo = 1 ORDER BY nombre ASC;');
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=1 AND activo = 1 ORDER BY nombre ASC;');
        $jefes_turno = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=4 AND activo = 1 ORDER BY nombre ASC;');
        $tipos_planilla = DB::select('SELECT cod_tipo_planilla, nombre FROM pst.dbo.tipo_planilla WHERE activo = 1 ORDER BY nombre ASC;');

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




        return view('index', compact('turnos', 'supervisores', 'planilleros', 'jefes_turno', 'planillasHoy', 'planillas7dias', 'noGuardado', 'tipos_planilla'));
    }

    public function planillas(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        // Datos obtenidos desde lomar_prod (líneas 34-73)
        $turnos = DB::select('SELECT id,nombre FROM [administracion].[dbo].[tipos_turno] WHERE activo=1 ORDER BY id ASC;');
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=2 AND activo = 1 ORDER BY nombre ASC;');
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=1 AND activo = 1 ORDER BY nombre ASC;');
        $jefes_turno = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=4 AND activo = 1 ORDER BY nombre ASC;');
        $tipos_planilla = DB::select('SELECT cod_tipo_planilla, nombre FROM pst.dbo.tipo_planilla WHERE activo = 1 ORDER BY nombre ASC;');

        // Obtener datos únicos desde lomar_prod para los filtros
        $empresas = DB::connection('lomar_prod')->select('SELECT DISTINCT empresa as descripcion FROM v_lotes_pst ORDER BY empresa ASC;');
        $procesos = DB::connection('lomar_prod')->select('SELECT DISTINCT proceso as nombre FROM v_lotes_pst ORDER BY proceso ASC;');
        $proveedores = DB::connection('lomar_prod')->select('SELECT DISTINCT proveedor as descripcion FROM v_lotes_pst ORDER BY proveedor ASC;');
        $especies = DB::connection('lomar_prod')->select('SELECT DISTINCT especie as descripcion FROM v_lotes_pst ORDER BY especie ASC;');

        $planillas = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('guardado', 1);

        // Aplicar filtro de rango de fechas
        if ($request->filled('fechaInicio') && $request->filled('fechaFin')) {
            $planillas->whereBetween('fec_turno', [
                $request->fechaInicio,
                $request->fechaFin
            ]);
        } elseif ($request->filled('fechaInicio')) {
            $planillas->whereDate('fec_turno', '>=', $request->fechaInicio);
        } elseif ($request->filled('fechaFin')) {
            $planillas->whereDate('fec_turno', '<=', $request->fechaFin);
        } else {
            // Si no hay filtros de fecha, mostrar solo los últimos 3 meses
            $tresMesesAtras = now()->subMonths(1)->format('Y-m-d');
            $planillas->whereDate('fec_turno', '>=', $tresMesesAtras);
        }

        // Aplicar filtro de lote
        if ($request->filled('filtroLote')) {
            $planillas->where('lote', 'LIKE', '%' . $request->filtroLote . '%');
        }

        if ($request->filled('filtroTurno') && $request->filtroTurno != ' ') {
            $planillas->where('turno', $request->filtroTurno);
        }

        if ($request->filled('filtroProv') && $request->filtroProv != ' ') {
            $planillas->where('proveedor', $request->filtroProv);
        }

        if ($request->filled('filtroEmpresa')) {
            $planillas->where('empresa', $request->filtroEmpresa);
        }

        if ($request->filled('filtroEspecie')) {
            $planillas->where('especie', $request->filtroEspecie);
        }

        if ($request->filled('filtroSupervisor')) {
            $planillas->where('cod_supervisor', $request->filtroSupervisor);
        }

        if ($request->filled('filtroPlanillero')) {
            $planillas->where('cod_planillero', $request->filtroPlanillero);
        }

        $planillas = $planillas->orderByDesc('fec_turno')->get();

        return view('admin.mantencion.planillas', compact(
            'turnos',
            'supervisores',
            'planilleros',
            'jefes_turno',
            'planillas',
            'tipos_planilla',
            'empresas',
            'procesos',
            'proveedores',
            'especies'
        ));
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

        // Buscar en lomar_prod usando la vista v_lotes_pst
        $result = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->where('nombre_lote', $loteValue)
            ->select('id_lote', 'nombre_lote', 'empresa', 'proveedor', 'especie', 'proceso', 'planta')
            ->first();

        if (!$result) {
            return response()->json(['error' => 'El lote no existe.'], 422);
        }

        // Retornar en el formato esperado por el frontend
        return response()->json([
            'id_lote' => $result->id_lote,
            'nombre_lote' => $result->nombre_lote,
            'empresa' => $result->empresa,
            'proveedor' => $result->proveedor,
            'especie' => $result->especie,
            'proceso' => $result->proceso,
            'planta' => $result->planta
        ]);
    }

    public function procesarFormulario(Request $request)
    {
        $request->validate([
            'tipo_planilla' => 'required',
            'codLote' => 'required',
            'empresa' => 'required',
            'proveedor' => 'required',
            'proceso' => 'required',
            'especie' => 'required',
            'fechaTurno' => 'required',
            'horaInicio' => 'required',
            'turno' => 'required',
            'supervisor' => 'required',
            'planillero' => 'required',
            'jefeTurno' => 'required',
        ]);

        // Verificar que el lote existe en lomar_prod
        $loteData = DB::connection('lomar_prod')
            ->table('v_lotes_pst')
            ->where('nombre_lote', $request->input('codLote'))
            ->first();

        if (!$loteData) {
            return response()->json(['error' => 'El lote no existe.'], 422);
        }

        try {
            // Insertar usando las nuevas columnas (strings de lomar_prod)
            DB::table('pst.dbo.planillas_pst')->insert([
                'cod_tipo_planilla' => $request->input('tipo_planilla'),
                'fec_turno' => $request->input('fechaTurno'),
                'hora_inicio' => $request->input('horaInicio'),
                'cod_turno' => $request->input('turno'),
                'cod_planillero' => $request->input('planillero'),
                'cod_supervisor' => $request->input('supervisor'),
                'cod_usuario_crea_planilla' => session('user.cod_usuario'),
                'cod_jefe_turno' => $request->input('jefeTurno'),
                'guardado' => 0,
                // Nuevas columnas de lomar_prod
                'lote_id_mp' => $loteData->id_lote,
                'lote_nombre' => $loteData->nombre_lote,
                'empresa_nombre' => $loteData->empresa,
                'proveedor_nombre' => $loteData->proveedor,
                'especie_nombre' => $loteData->especie,
                'proceso_nombre' => $loteData->proceso,
                'planta_nombre' => $loteData->planta,
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

        $query = DB::table('pst.dbo.v_planilla_pst')->select('*')->orderByDesc('fec_turno')->where('guardado', 1);

        if (!empty($filtroLote)) {
            // Usar la nueva columna lote_nombre en lugar de lote
            $query->where('lote_nombre', 'LIKE', '%' . $filtroLote . '%');
        }

        $resultados = $query->get();

        return response()->json(['planillas' => $resultados]);
    }

}
