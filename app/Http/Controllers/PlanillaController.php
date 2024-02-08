<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Database\QueryException;

class PlanillaController extends Controller
{

    public function verPlanilla($idPlanilla)
    {

        if (!session('user')) {
            return redirect('/login');
        }

        $detalle_planilla = DB::table('pst.dbo.detalle_planilla_pst ')
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->first();

        $desc_planilla = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->first();

        if (!$desc_planilla) {
            // La planilla no existe, redirigir al usuario a la página de inicio
            return redirect('/inicio')->with('error', 'La planilla no xiste.');
        }


        $planilla = DB::table("pst.dbo.v_registro_planilla_pst")
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->get();

        $subtotal = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select('fin.nombre AS cFinal', DB::raw('SUM(rp.piezas) AS subtotalPiezas'), DB::raw('SUM(rp.kilos) AS subtotalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('fin.nombre', 'rp.cod_planilla')
            ->orderBy('fin.nombre')
            ->get();

        $total = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select(DB::raw('SUM(rp.piezas) AS totalPiezas'), DB::raw('SUM(rp.kilos) AS totalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('rp.cod_planilla')
            ->orderBy('rp.cod_planilla')
            ->get();


        return view('vista-planilla', compact('subtotal', 'total', 'planilla', 'idPlanilla', 'detalle_planilla', 'desc_planilla'));
    }

    public function mostrarPlanilla($idPlanilla)
    {

        if (!session('user')) {
            return redirect('/login');
        }

        $cortes = DB::select('SELECT cod_corte,nombre FROM pst.dbo.corte WHERE activo = 1 GROUP BY nombre,cod_corte ORDER BY nombre ASC;');
        $salas = DB::select('SELECT cod_sala,nombre FROM pst.dbo.sala WHERE activo = 1 ORDER BY nombre ASC;');
        $calibres = DB::select('SELECT cod_calib,nombre FROM pst.dbo.calibre WHERE activo = 1  ;');
        $calidades = DB::select('SELECT cod_cald,nombre FROM pst.dbo.calidad WHERE activo = 1 ORDER BY nombre ASC;');
        $destinos = DB::select('SELECT cod_destino,nombre FROM pst.dbo.destino WHERE activo = 1 ORDER BY nombre ASC;');

        $empresas = DB::select('SELECT cod_empresa,descripcion FROM bdsystem.dbo.empresas WHERE inactivo=0 ORDER BY descripcion ASC;');
        $procesos = DB::select('SELECT cod_sproceso,UPPER(nombre) as nombre FROM bdsystem.dbo.subproceso WHERE inactivo=0 ORDER BY nombre ASC;');
        $proveedores = DB::select('SELECT cod_proveedor,descripcion FROM bdsystem.dbo.proveedores WHERE inactivo=0 ORDER BY descripcion ASC;');
        $especies = DB::select('SELECT cod_especie,descripcion FROM bdsystem.dbo.especies WHERE inactivo=0 ORDER BY descripcion ASC;');
        $turnos = DB::select('SELECT codTurno,NomTurno FROM bdsystem.dbo.turno WHERE inactivo=0 ORDER BY NomTurno ASC;');
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=2 AND activo = 1 ORDER BY nombre ASC;');
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst.dbo.v_data_usuario WHERE cod_rol=1 AND activo = 1 ORDER BY nombre ASC;');


        $detalle_planilla = DB::table('pst.dbo.detalle_planilla_pst ')
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->first();

        $desc_planilla = DB::table('pst.dbo.v_planilla_pst')
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->first();

        if (!$desc_planilla) {
            // La planilla no existe, redirigir al usuario a la página de inicio
            return redirect('/inicio')->withe('error', 'La planilla no xiste.');
        }


        $planilla = DB::table("pst.dbo.v_registro_planilla_pst")
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->get();

        $subtotal = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select('fin.nombre AS cFinal', DB::raw('SUM(rp.piezas) AS subtotalPiezas'), DB::raw('SUM(rp.kilos) AS subtotalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('fin.nombre', 'rp.cod_planilla')
            ->orderBy('fin.nombre')
            ->get();

        $total = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select(DB::raw('SUM(rp.piezas) AS totalPiezas'), DB::raw('SUM(rp.kilos) AS totalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('rp.cod_planilla')
            ->orderBy('rp.cod_planilla')
            ->get();


        return view('planilla', compact('subtotal', 'total', 'planilla', 'destinos', 'cortes', 'salas', 'calibres', 'calidades', 'idPlanilla', 'detalle_planilla', 'desc_planilla', 'empresas', 'procesos', 'proveedores', 'especies', 'turnos', 'supervisores', 'planilleros'));
    }


    public function agregarRegistro(Request $request)
    {
        $idPlanilla = $request->input('idPlanilla');
        $codCorteIni = $request->input('cInicial');
        $codCorteFin = $request->input('cFinal');
        $codCalibre = $request->input('calibre');
        $codDestino = $request->input('destino');
        $codCalidad = $request->input('calidad');
        $piezas = $request->input('piezas');
        $kilos = $request->input('kilos');


        $newCalibre = $request->input('newCalibre');
        $newCalidad = $request->input('newCalidad');
        $newDestino = $request->input('newDestino');
        $newCorteIni = $request->input('newCorteIni');
        $newCorteFin = $request->input('newCorteFin');

        $error = false;
        $errorDestino = $errorCorteFin = $errorCorteIni = $errorCorte = $errorCalibre = $errorCalidad = null;






        $existingDestino = DB::table('pst.dbo.destino')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newDestino))])
            ->first();

        if ($codDestino === "nuevo") {


            if (!$existingDestino) {
                $id_newDestino = DB::table('pst.dbo.destino')->insertGetId([
                    'nombre' => $newDestino,
                    'activo' => 1,
                ]);
                $codDestino = $id_newDestino;
            } else {
                $errorDestino = 'El destino ya existe en la base de datos';
                $error = true;
            }
        }
        $existingCorteIni = DB::table('pst.dbo.corte')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCorteIni))])
            ->first();

        if ($codCorteIni === "nuevo") {


            if (!$existingCorteIni) {
                $id_newCorteIni = DB::table('pst.dbo.corte')->insertGetId([
                    'nombre' => $newCorteIni,
                    'activo' => 1
                ]);

                $codCorteIni = $id_newCorteIni;



            } else {
                $errorCorte = 'El corte ya existe en la base de datos';
                $error = true;
            }
        }
        $existingCorteFin = DB::table('pst.dbo.corte')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCorteFin))])
            ->first();
        if ($codCorteFin === "nuevo") {


            if (!$existingCorteFin) {
                $id_newCorteFin = DB::table('pst.dbo.corte')->insertGetId([
                    'nombre' => $newCorteFin,
                    'activo' => 1,
                ]);

                $codCorteFin = $id_newCorteFin;



            } else {
                $errorCorte = 'El corte ya existe en la base de datos';
                $error = true;
            }
        }
        $existingCalibre = DB::table('pst.dbo.calibre')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCalibre))])
            ->first();

        if ($codCalibre === "nuevo") {


            if (!$existingCalibre) {
                $id_newCalibre = DB::table('pst.dbo.calibre')->insertGetId([
                    'nombre' => $newCalibre,
                    'activo' => 1
                ]);
                $codCalibre = $id_newCalibre;
            } else {
                $errorCalibre = 'El calibre ya existe en la base de datos';
                $error = true;
            }
        }
        $existingCalidad = DB::table('pst.dbo.calidad')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCalidad))])
            ->first();
        if ($codCalidad === "nuevo") {


            if (!$existingCalidad) {
                $id_newCalidad = DB::table('pst.dbo.calidad')->insertGetId([
                    'nombre' => $newCalidad,
                    'activo' => 1
                ]);
                $codCalidad = $id_newCalidad;
            } else {
                $errorCalidad = 'La calidad ya existe en la base de datos';
                $error = true;
            }
        }


        if ($error) {
            return response()->json(['success' => false, 'mensaje' => 'Ha ocurrido un error.', 'errores' => compact('errorDestino', 'errorCorte', 'errorCorteFin', 'errorCalibre', 'errorCalidad')]);
        }




        DB::table('pst.dbo.registro_planilla_pst')->insert([
            'cod_planilla' => $idPlanilla,
            'cod_corte_ini' => $codCorteIni,
            'cod_corte_fin' => $codCorteFin,
            'cod_destino' => $codDestino,
            'cod_calibre' => $codCalibre,
            'cod_calidad' => $codCalidad,
            'piezas' => $piezas,
            'kilos' => $kilos,
            'guardado' => 0,
        ]);


        $planillaActualizada = DB::table("pst.dbo.v_registro_planilla_pst")
            ->where('cod_planilla', $idPlanilla)
            ->select('*')
            ->get();

        $subtotal = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select('fin.nombre AS cFinal', DB::raw('SUM(rp.piezas) AS subtotalPiezas'), DB::raw('SUM(rp.kilos) AS subtotalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('fin.nombre', 'rp.cod_planilla')
            ->orderBy('fin.nombre')
            ->get();

        $total = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select(DB::raw('SUM(rp.piezas) AS totalPiezas'), DB::raw('SUM(rp.kilos) AS totalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('rp.cod_planilla')
            ->orderBy('rp.cod_planilla')
            ->get();

        return response()->json(['success' => true, 'mensaje' => 'Registro agregado exitosamente', 'planilla' => $planillaActualizada, 'subtotal' => $subtotal, 'total' => $total]);

    }

    public function modificarPlanilla(Request $request, $id)
    {
        $id = intval($id);

        // Recuperar los datos modificados del formulario
        $modifiedFields = $request->all();

        // Obtener la planilla actual
        $planilla = DB::table('pst.dbo.planillas_pst')->where('cod_planilla', $id)->first();

        // Verificar si la planilla existe
        if (!$planilla) {
            return response()->json(['success' => false, 'mensaje' => 'La planilla con ID ' . $id . ' no existe.']);
        }

        // Construir una cadena de actualización SQL
        $sql = "UPDATE pst.dbo.planillas_pst SET ";
        $updates = [];

        // Identificar los campos que se quieren ingresar
        if (isset($modifiedFields['fechaTurno'])) {
            $updates[] = "fec_turno = '" . $modifiedFields['fechaTurno'] . "'";
        }

        if (isset($modifiedFields['turno'])) {
            $updates[] = "cod_turno = '" . $modifiedFields['turno'] . "'";
        }

        if (isset($modifiedFields['supervisor'])) {
            $updates[] = "cod_supervisor = '" . $modifiedFields['supervisor'] . "'";
        }

        if (isset($modifiedFields['planillero'])) {
            $updates[] = "cod_planillero = '" . $modifiedFields['planillero'] . "'";
        }

        // Verificar si hay campos para actualizar
        if (!empty($updates)) {
            $sql .= implode(', ', $updates);
            $sql .= " WHERE cod_planilla = $id";

            // Ejecutar la consulta SQL
            DB::update($sql);

            return response()->json(['success' => true, 'mensaje' => 'Planilla actualizada exitosamente.']);
        } else {
            return response()->json(['success' => false, 'mensaje' => 'No se realizaron cambios en la planilla.']);
        }
    }

    public function guardarPlanilla(Request $request)
    {
        try {
            $cajasEntrega = $request->input('cajas_entrega');
            $kilosEntrega = $request->input('kilos_entrega');
            $codSala = $request->input('sala');
            $piezasEntrega = $request->input('piezas_entrega');
            $cajasRecepcion = $request->input('cajas_recepcion');
            $kilosRecepcion = $request->input('kilos_recepcion');
            $piezasRecepcion = $request->input('piezas_recepcion');
            $dotacion = $request->input('dotacion');
            $observacion = $request->input('observacion');

            DB::table('pst.dbo.detalle_planilla_pst')
                ->where('cod_planilla', $request->input('idPlanilla'))
                ->update([
                    'cajas_entrega' => $cajasEntrega,
                    'kilos_entrega' => $kilosEntrega,
                    'piezas_entrega' => $piezasEntrega,
                    'cajas_recepcion' => $cajasRecepcion,
                    'kilos_recepcion' => $kilosRecepcion,
                    'piezas_recepcion' => $piezasRecepcion,
                    'dotacion' => $dotacion,
                    'cod_sala' => $codSala,
                    'observacion' => $observacion,
                ]);


            DB::table('pst.dbo.planillas_pst')
                ->where('cod_planilla', $request->input('idPlanilla'))
                ->update(['guardado' => 1]);

            DB::table('pst.dbo.registro_planilla_pst')
                ->where('cod_planilla', $request->input('idPlanilla'))
                ->update(['guardado' => 1]);

            $_SESSION['planillaSave'] = true;

            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            // Manejar la excepción de la consulta SQL
            $errorMessage = $e->getMessage();
            return response()->json(['success' => false, 'error' => $errorMessage]);
        }
    }
    public function obtenerDatosFila($id)
    {
        // Lógica para obtener los datos de la fila con el ID proporcionado
        $datos = DB::table("pst.dbo.registro_planilla_pst")
            ->select('*')
            ->where('cod_reg', $id)
            ->first();

        // Devuelve los datos en formato JSON
        return response()->json($datos);
    }

    public function editarRegistro(Request $request)
    {
        $idPlanilla = $request->input('idPlanilla');
        $idRegistro = $request->input('idRegistro');
        $codCorteIni = $request->input('cInicialEditar');
        $codCorteFin = $request->input('cFinalEditar');
        $codCalibre = $request->input('calibreEditar');
        $codDestino = $request->input('destinoEditar');
        $codCalidad = $request->input('calidadEditar');
        $piezas = $request->input('piezasEditar');
        $kilos = $request->input('kilosEditar');

        DB::table('pst.dbo.registro_planilla_pst')
            ->where('cod_reg', $idRegistro)
            ->update([
                'cod_corte_ini' => $codCorteIni,
                'cod_corte_fin' => $codCorteFin,
                'cod_destino' => $codDestino,
                'cod_calibre' => $codCalibre,
                'cod_calidad' => $codCalidad,
                'piezas' => $piezas,
                'kilos' => $kilos
            ]);

        $planillaActualizada = DB::table("pst.dbo.v_registro_planilla_pst")
            ->where('cod_planilla', $idPlanilla)
            ->select('*')
            ->get();

        $subtotal = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select('fin.nombre AS cFinal', DB::raw('SUM(rp.piezas) AS subtotalPiezas'), DB::raw('SUM(rp.kilos) AS subtotalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('fin.nombre', 'rp.cod_planilla')
            ->orderBy('fin.nombre')
            ->get();

        $total = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select(DB::raw('SUM(rp.piezas) AS totalPiezas'), DB::raw('SUM(rp.kilos) AS totalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('rp.cod_planilla')
            ->orderBy('rp.cod_planilla')
            ->get();

        return response()->json(['success' => true, 'mensaje' => 'Registro agregado exitosamente', 'planilla' => $planillaActualizada, 'subtotal' => $subtotal, 'total' => $total]);



    }
    public function eliminarRegistro(Request $request)
    {
        $idPlanilla = $request->input('idPlanilla');

        $planillaActualizada = DB::table("pst.dbo.v_registro_planilla_pst")
            ->where('cod_planilla', $idPlanilla)
            ->select('*')
            ->get();

        $subtotal = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select('fin.nombre AS cFinal', DB::raw('SUM(rp.piezas) AS subtotalPiezas'), DB::raw('SUM(rp.kilos) AS subtotalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('fin.nombre', 'rp.cod_planilla')
            ->orderBy('fin.nombre')
            ->get();

        $total = DB::table('pst.dbo.registro_planilla_pst AS rp')
            ->select(DB::raw('SUM(rp.piezas) AS totalPiezas'), DB::raw('SUM(rp.kilos) AS totalKilos'))
            ->leftJoin('pst.dbo.corte AS fin', 'rp.cod_corte_fin', '=', 'fin.cod_corte')
            ->where('rp.cod_planilla', '=', $idPlanilla)
            ->groupBy('rp.cod_planilla')
            ->orderBy('rp.cod_planilla')
            ->get();
        if ($request->has('ids')) {
            // Obtener los IDs de la solicitud
            $idsAEliminar = $request->input('ids');

            DB::table('pst.dbo.registro_planilla_pst')->whereIn('cod_reg', $idsAEliminar)->delete();


            return response()->json(['success' => true, 'message' => 'Registros eliminados correctamente', 'planilla' => $planillaActualizada, 'subtotal' => $subtotal, 'total' => $total]);
        }

        return response()->json(['success' => false, 'message' => 'No se proporcionaron IDs para eliminar', 'planilla' => $planillaActualizada, 'subtotal' => $subtotal, 'total' => $total]);
    }

}


