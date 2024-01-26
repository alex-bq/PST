<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Database\QueryException;

class PlanillaController extends Controller
{

    public function mostrarPlanilla($idPlanilla)
    {

        if (!session('user')) {
            return redirect('/login');
        }

        $cortes = DB::select('SELECT cod_corte,nombre FROM pst.dbo.corte WHERE inactivo = 0 AND transito = 1 GROUP BY nombre,cod_corte ORDER BY nombre ASC;');
        $procesos = DB::select('SELECT cod_sproceso,nombre FROM pst.dbo.subproceso WHERE inactivo = 0 ORDER BY nombre ASC;');
        $calibres = DB::select('SELECT cod_calib,nombre FROM pst.dbo.calibre WHERE inactivo = 0 AND transito = 1 ;');
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
        $codDestino = $request->input('destino');
        $codCalidad = $request->input('calidad');
        $piezas = $request->input('piezas');
        $kilos = $request->input('kilos');


        $newCalibre = $request->input('newCalibre');
        $newCalidad = $request->input('newCalidad');
        $newDestino = $request->input('newDestino');
        $newCorte = $request->input('newCorte');

        $error = false;
        $errorDestino = $errorCorte = $errorCorteFin = $errorCalibre = $errorCalidad = null;

        if ($codDestino === "nuevo") {
            $existingDestino = DB::table('pst.dbo.destino')
                ->select('nombre')
                ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newDestino))])
                ->first();

            if (!$existingDestino) {
                $id_newDestino = DB::table('pst.dbo.destino')->insertGetId([
                    'nombre' => $newDestino,
                    'inactivo' => 0,
                ]);
                $codDestino = $id_newDestino;
            } else {
                $errorDestino = 'El destino ya existe en la base de datos';
                $error = true;
            }
        }

        if ($codCorteIni === "nuevo" || $codCorteFin === "nuevo") {
            $existingCorte = DB::table('pst.dbo.corte')
                ->select('nombre')
                ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCorte))])
                ->first();

            if (!$existingCorte) {
                $id_newCorte = DB::table('pst.dbo.corte')->insertGetId([
                    'nombre' => $newCorte,
                    'inactivo' => 0,
                    'transito' => 1
                ]);
                if ($codCorteIni === "nuevo") {
                    $codCorteIni = $id_newCorte;
                }
                if ($codCorteFin === "nuevo") {
                    $codCorteFin = $id_newCorte;
                }

            } else {
                $errorCorte = 'El corte ya existe en la base de datos';
                $error = true;
            }
        }


        if ($codCalibre === "nuevo") {
            $existingCalibre = DB::table('pst.dbo.calibre')
                ->select('nombre')
                ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCalibre))])
                ->first();

            if (!$existingCalibre) {
                $id_newCalibre = DB::table('pst.dbo.calibre')->insertGetId([
                    'nombre' => $newCalibre,
                    'inactivo' => 0,
                    'transito' => 1
                ]);
                $codCalibre = $id_newCalibre;
            } else {
                $errorCalibre = 'El calibre ya existe en la base de datos';
                $error = true;
            }
        }

        if ($codCalidad === "nuevo") {
            $existingCalidad = DB::table('pst.dbo.calidad')
                ->select('nombre')
                ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCalidad))])
                ->first();

            if (!$existingCalidad) {
                $id_newCalidad = DB::table('pst.dbo.calidad')->insertGetId([
                    'nombre' => $newCalidad,
                    'inactivo' => 0,
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
            $piezasEntrega = $request->input('piezas_entrega');
            $cajasRecepcion = $request->input('cajas_recepcion');
            $kilosRecepcion = $request->input('kilos_recepcion');
            $piezasRecepcion = $request->input('piezas_recepcion');
            $dotacion = $request->input('dotacion');
            $observacion = $request->input('observacion');

            DB::table('pst.dbo.detalle_planilla_pst')->insert([
                'cod_planilla' => $request->input('idPlanilla'),
                'cajas_entrega' => $cajasEntrega,
                'kilos_entrega' => $kilosEntrega,
                'piezas_entrega' => $piezasEntrega,
                'cajas_recepcion' => $cajasRecepcion,
                'kilos_recepcion' => $kilosRecepcion,
                'piezas_recepcion' => $piezasRecepcion,
                'dotacion' => $dotacion,
                'observacion' => $observacion,
            ]);

            DB::table('pst.dbo.planillas_pst')
                ->where('cod_planilla', $request->input('idPlanilla'))
                ->update(['guardado' => 1]);

            DB::table('pst.dbo.registro_planilla_pst')
                ->where('cod_planilla', $request->input('idPlanilla'))
                ->update(['guardado' => 1]);

            return redirect('/inicio');
        } catch (QueryException $e) {
            // Manejar la excepción de la consulta SQL
            return redirect('/inicio')->with('error', 'Error al guardar la planilla.');
        }
    }

}


