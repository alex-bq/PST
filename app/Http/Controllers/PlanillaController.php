<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Database\QueryException;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;

class PlanillaController extends Controller
{

    public function verPlanilla($idPlanilla)
    {

        if (!session('user')) {
            return redirect('/login');
        }

        $detalle_planilla = DB::table('pst_2.dbo.detalle_planilla_pst ')
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->first();

        $desc_planilla = DB::table('pst_2.dbo.v_planilla_pst')
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->first();

        if (!$desc_planilla) {
            // La planilla no existe, redirigir al usuario a la página de inicio
            return redirect('/inicio')->with('error', 'La planilla no existe.');
        }


        $planilla = DB::table("pst_2.dbo.v_registro_planilla_pst")
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->get();

        $subtotal = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
            ->select(
                'c.nombre AS corte_final',
                'cal.nombre AS calidad',
                DB::raw('SUM(rp.piezas) AS total_piezas'),
                DB::raw('SUM(rp.kilos) AS total_kilos'),
                DB::raw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) AS porcentaje_del_total')
            )
            ->join('pst_2.dbo.corte AS c', 'rp.cod_corte_fin', '=', 'c.cod_corte')
            ->join('pst_2.dbo.calidad AS cal', 'rp.cod_calidad', '=', 'cal.cod_cald')
            ->where('rp.cod_planilla', $idPlanilla)
            ->groupBy('c.nombre', 'cal.nombre')
            ->orderByRaw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) DESC')
            ->orderBy('cal.nombre', 'asc')
            ->orderBy('c.nombre', 'asc')
            ->get();

        $total = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
            ->select(
                DB::raw("' ' as corte_final"),
                DB::raw("'Total' as calidad"),
                DB::raw('SUM(rp.piezas) AS total_piezas'),
                DB::raw('SUM(rp.kilos) AS total_kilos'),
                DB::raw('100.0 AS porcentaje_del_total')
            )
            ->where('rp.cod_planilla', $idPlanilla)
            ->get();


        return view('vista-planilla', compact('subtotal', 'total', 'planilla', 'idPlanilla', 'detalle_planilla', 'desc_planilla'));
    }


    public function mostrarPlanilla($idPlanilla)
    {

        if (!session('user')) {
            return redirect('/login');
        }

        $cortes = DB::select('SELECT cod_corte,nombre FROM pst_2.dbo.corte WHERE activo = 1 GROUP BY nombre,cod_corte ORDER BY nombre ASC;');
        $salas = DB::select('SELECT cod_sala,nombre FROM pst_2.dbo.sala WHERE activo = 1 ORDER BY nombre ASC;');
        $calibres = DB::select('SELECT cod_calib,nombre FROM pst_2.dbo.calibre WHERE activo = 1  ;');
        $calidades = DB::select('SELECT cod_cald,nombre FROM pst_2.dbo.calidad WHERE activo = 1 ORDER BY nombre ASC;');
        $destinos = DB::select('SELECT cod_destino,nombre FROM pst_2.dbo.destino WHERE activo = 1 ORDER BY nombre ASC;');

        $empresas = DB::select('SELECT cod_empresa,descripcion FROM bdsystem.dbo.empresas WHERE inactivo=0 ORDER BY descripcion ASC;');
        $procesos = DB::select('SELECT cod_sproceso,UPPER(nombre) as nombre FROM bdsystem.dbo.subproceso WHERE inactivo=0 ORDER BY nombre ASC;');
        $proveedores = DB::select('SELECT cod_proveedor,descripcion FROM bdsystem.dbo.proveedores WHERE inactivo=0 ORDER BY descripcion ASC;');
        $especies = DB::select('SELECT cod_especie,descripcion FROM bdsystem.dbo.especies WHERE inactivo=0 ORDER BY descripcion ASC;');
        $turnos = DB::select('SELECT codTurno,NomTurno FROM bdsystem.dbo.turno WHERE inactivo=0 ORDER BY NomTurno ASC;');
        $supervisores = DB::select('SELECT cod_usuario,nombre FROM pst_2.dbo.v_data_usuario WHERE cod_rol=2 AND activo = 1 ORDER BY nombre ASC;');
        $planilleros = DB::select('SELECT cod_usuario,nombre FROM pst_2.dbo.v_data_usuario WHERE cod_rol=1 AND activo = 1 ORDER BY nombre ASC;');
        $jefes_turno = DB::select('SELECT cod_usuario,nombre FROM pst_2.dbo.v_data_usuario WHERE cod_rol=4 AND activo = 1 ORDER BY nombre ASC;');


        $detalle_planilla = DB::table('pst_2.dbo.detalle_planilla_pst ')
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->first();

        $desc_planilla = DB::table('pst_2.dbo.v_planilla_pst')
            ->select('*')
            ->where('cod_planilla', $idPlanilla)
            ->first();

        if (!$desc_planilla) {
            // La planilla no existe, redirigir al usuario a la página de inicio
            return redirect('/main')->with('error', 'La planilla no existe.');
        }

        // Obtener el tipo de planilla
        $tipo_planilla = DB::table('pst_2.dbo.tipo_planilla')
            ->where('cod_tipo_planilla', $desc_planilla->cod_tipo_planilla)
            ->first();

        if (($desc_planilla->cod_planillero == session('user.cod_usuario') && $desc_planilla->guardado == 0) || !(session('user.cod_rol') == 1)) {


            $planilla = DB::table("pst_2.dbo.v_registro_planilla_pst")
                ->select('*')
                ->where('cod_planilla', $idPlanilla)
                ->get();

            $subtotal = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
                ->select(
                    'c.nombre AS corte_final',
                    'cal.nombre AS calidad',
                    DB::raw('SUM(rp.piezas) AS total_piezas'),
                    DB::raw('SUM(rp.kilos) AS total_kilos'),
                    DB::raw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) AS porcentaje_del_total')
                )
                ->join('pst_2.dbo.corte AS c', 'rp.cod_corte_fin', '=', 'c.cod_corte')
                ->join('pst_2.dbo.calidad AS cal', 'rp.cod_calidad', '=', 'cal.cod_cald')
                ->where('rp.cod_planilla', $idPlanilla)
                ->groupBy('c.nombre', 'cal.nombre')
                ->orderByRaw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) DESC')
                ->orderBy('cal.nombre', 'asc')
                ->orderBy('c.nombre', 'asc')
                ->get();

            $total = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
                ->select(
                    DB::raw("' ' as corte_final"),
                    DB::raw("'Total' as calidad"),
                    DB::raw('SUM(rp.piezas) AS total_piezas'),
                    DB::raw('SUM(rp.kilos) AS total_kilos'),
                    DB::raw('100.0 AS porcentaje_del_total')
                )
                ->where('rp.cod_planilla', $idPlanilla)
                ->get();


            return view('planilla', compact('subtotal', 'total', 'planilla', 'destinos', 'cortes', 'salas', 'calibres', 'calidades', 'idPlanilla', 'detalle_planilla', 'desc_planilla', 'empresas', 'procesos', 'proveedores', 'especies', 'turnos', 'supervisores', 'planilleros', 'jefes_turno', 'tipo_planilla'));
        } else {
            return redirect('/inicio')->with('error', 'No tiene permiso para editar la planilla, indicar a su supervisor');
        }
    }


    public function agregarRegistro(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        }
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






        $existingDestino = DB::table('pst_2.dbo.destino')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newDestino))])
            ->first();

        if ($codDestino === "nuevo") {


            if (!$existingDestino) {
                $id_newDestino = DB::table('pst_2.dbo.destino')->insertGetId([
                    'nombre' => $newDestino,
                    'activo' => 1,
                ]);
                $codDestino = $id_newDestino;
            } else {
                $errorDestino = 'El destino ya existe en la base de datos';
                $error = true;
            }
        }
        $existingCorteIni = DB::table('pst_2.dbo.corte')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCorteIni))])
            ->first();

        if ($codCorteIni === "nuevo") {


            if (!$existingCorteIni) {
                $id_newCorteIni = DB::table('pst_2.dbo.corte')->insertGetId([
                    'nombre' => $newCorteIni,
                    'activo' => 1
                ]);

                $codCorteIni = $id_newCorteIni;



            } else {
                $errorCorte = 'El corte ya existe en la base de datos';
                $error = true;
            }
        }
        $existingCorteFin = DB::table('pst_2.dbo.corte')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCorteFin))])
            ->first();
        if ($codCorteFin === "nuevo") {


            if (!$existingCorteFin) {
                $id_newCorteFin = DB::table('pst_2.dbo.corte')->insertGetId([
                    'nombre' => $newCorteFin,
                    'activo' => 1,
                ]);

                $codCorteFin = $id_newCorteFin;



            } else {
                $errorCorte = 'El corte ya existe en la base de datos';
                $error = true;
            }
        }
        $existingCalibre = DB::table('pst_2.dbo.calibre')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCalibre))])
            ->first();

        if ($codCalibre === "nuevo") {


            if (!$existingCalibre) {
                $id_newCalibre = DB::table('pst_2.dbo.calibre')->insertGetId([
                    'nombre' => $newCalibre,
                    'activo' => 1
                ]);
                $codCalibre = $id_newCalibre;
            } else {
                $errorCalibre = 'El calibre ya existe en la base de datos';
                $error = true;
            }
        }
        $existingCalidad = DB::table('pst_2.dbo.calidad')
            ->select('nombre')
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '')) = ?", [strtolower(str_replace(' ', '', $newCalidad))])
            ->first();
        if ($codCalidad === "nuevo") {


            if (!$existingCalidad) {
                $id_newCalidad = DB::table('pst_2.dbo.calidad')->insertGetId([
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




        DB::table('pst_2.dbo.registro_planilla_pst')->insert([
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


        $planillaActualizada = DB::table("pst_2.dbo.v_registro_planilla_pst")
            ->where('cod_planilla', $idPlanilla)
            ->select('*')
            ->get();

        $subtotal = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
            ->select(
                'c.nombre AS corte_final',
                'cal.nombre AS calidad',
                DB::raw('SUM(rp.piezas) AS total_piezas'),
                DB::raw('SUM(rp.kilos) AS total_kilos'),
                DB::raw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) AS porcentaje_del_total')
            )
            ->join('pst_2.dbo.corte AS c', 'rp.cod_corte_fin', '=', 'c.cod_corte')
            ->join('pst_2.dbo.calidad AS cal', 'rp.cod_calidad', '=', 'cal.cod_cald')
            ->where('rp.cod_planilla', $idPlanilla)
            ->groupBy('c.nombre', 'cal.nombre')
            ->orderByRaw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) DESC')
            ->orderBy('cal.nombre', 'asc')
            ->orderBy('c.nombre', 'asc')
            ->get();

        $total = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
            ->select(
                DB::raw("' ' as corte_final"),
                DB::raw("'Total' as calidad"),
                DB::raw('SUM(rp.piezas) AS total_piezas'),
                DB::raw('SUM(rp.kilos) AS total_kilos'),
                DB::raw('100.0 AS porcentaje_del_total')
            )
            ->where('rp.cod_planilla', $idPlanilla)
            ->get();

        return response()->json(['success' => true, 'mensaje' => 'Registro agregado exitosamente', 'planilla' => $planillaActualizada, 'subtotal' => $subtotal, 'total' => $total]);

    }

    public function modificarPlanilla(Request $request, $id)
    {
        if (!session('user')) {
            return redirect('/login');
        }
        $id = intval($id);

        // Recuperar los datos modificados del formulario
        $modifiedFields = $request->all();

        // Obtener la planilla actual
        $planilla = DB::table('pst_2.dbo.planillas_pst')->where('cod_planilla', $id)->first();

        // Verificar si la planilla existe
        if (!$planilla) {
            return response()->json(['success' => false, 'mensaje' => 'La planilla con ID ' . $id . ' no existe.']);
        }

        // Construir una cadena de actualización SQL
        $sql = "UPDATE pst_2.dbo.planillas_pst SET ";
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

        if (isset($modifiedFields['jefe_turno'])) {
            $updates[] = "cod_jefe_turno = '" . $modifiedFields['jefe_turno'] . "'";
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
            // Validar que se haya enviado la hora de término
            if (!$request->input('hora_termino')) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Por favor, ingrese la hora de término'
                ]);
            }

            // Obtener la planilla primero y validar que existe
            $planilla = DB::table('pst_2.dbo.planillas_pst')
                ->where('cod_planilla', $request->input('idPlanilla'))
                ->first();

            if (!$planilla) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'No se encontró la planilla'
                ]);
            }

            // Convertir las horas a objetos DateTime para comparar
            $fechaBase = '2000-01-01';
            $inicio = new \DateTime($fechaBase . ' ' . $planilla->hora_inicio);
            $termino = new \DateTime($fechaBase . ' ' . $request->input('hora_termino'));

            // Si la hora de término es menor que la de inicio, asumimos que es del día siguiente
            if ($termino < $inicio) {
                $termino->modify('+1 day');

                // Validar que la diferencia no sea mayor a 12 horas
                $diferencia = $inicio->diff($termino);
                $horasDiferencia = $diferencia->h + ($diferencia->days * 24);

                if ($horasDiferencia > 12) {
                    return response()->json([
                        'success' => false,
                        'mensaje' => 'La diferencia entre hora de inicio y término no puede ser mayor a 12 horas'
                    ]);
                }
            }

            // Actualizar detalle_planilla_pst
            DB::table('pst_2.dbo.detalle_planilla_pst')
                ->where('cod_planilla', $request->input('idPlanilla'))
                ->update([
                    'cajas_entrega' => $request->input('cajas_entrega', 0),
                    'kilos_entrega' => $request->input('kilos_entrega', 0),
                    'piezas_entrega' => $request->input('piezas_entrega', 0),
                    'cajas_recepcion' => $request->input('cajas_recepcion', 0),
                    'kilos_recepcion' => $request->input('kilos_recepcion', 0),
                    'piezas_recepcion' => $request->input('piezas_recepcion', 0),
                    'dotacion' => $request->input('dotacion', 0),
                    'cod_sala' => $request->input('sala'),
                    'observacion' => $request->input('observacion', ''),
                    'productividad' => $request->input('productividad', 0),
                    'rendimiento' => $request->input('rendimiento', 0),
                    'embolsado_terminado' => $request->input('embolsado_terminado', 0),
                    'kilos_terminado' => $request->input('kilos_terminado', 0)
                ]);

            // Actualizar planilla
            DB::table('pst_2.dbo.planillas_pst')
                ->where('cod_planilla', $request->input('idPlanilla'))
                ->update([
                    'guardado' => 1,
                    'hora_termino' => $request->input('hora_termino'),
                    'tiempo_trabajado' => $request->input('tiempo_trabajado')
                ]);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    public function obtenerDatosFila($id)
    {
        // Lógica para obtener los datos de la fila con el ID proporcionado
        $datos = DB::table("pst_2.dbo.registro_planilla_pst")
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

        DB::table('pst_2.dbo.registro_planilla_pst')
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

        $planillaActualizada = DB::table("pst_2.dbo.v_registro_planilla_pst")
            ->where('cod_planilla', $idPlanilla)
            ->select('*')
            ->get();

        $subtotal = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
            ->select(
                'c.nombre AS corte_final',
                'cal.nombre AS calidad',
                DB::raw('SUM(rp.piezas) AS total_piezas'),
                DB::raw('SUM(rp.kilos) AS total_kilos'),
                DB::raw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) AS porcentaje_del_total')
            )
            ->join('pst_2.dbo.corte AS c', 'rp.cod_corte_fin', '=', 'c.cod_corte')
            ->join('pst_2.dbo.calidad AS cal', 'rp.cod_calidad', '=', 'cal.cod_cald')
            ->where('rp.cod_planilla', $idPlanilla)
            ->groupBy('c.nombre', 'cal.nombre')
            ->orderByRaw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) DESC')
            ->orderBy('cal.nombre', 'asc')
            ->orderBy('c.nombre', 'asc')
            ->get();

        $total = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
            ->select(
                DB::raw("' ' as corte_final"),
                DB::raw("'Total' as calidad"),
                DB::raw('SUM(rp.piezas) AS total_piezas'),
                DB::raw('SUM(rp.kilos) AS total_kilos'),
                DB::raw('100.0 AS porcentaje_del_total')
            )
            ->where('rp.cod_planilla', $idPlanilla)
            ->get();

        return response()->json(['success' => true, 'mensaje' => 'Registro agregado exitosamente', 'planilla' => $planillaActualizada, 'subtotal' => $subtotal, 'total' => $total]);



    }
    public function eliminarRegistro(Request $request)
    {

        if ($request->has('ids')) {
            // Obtener los IDs de la solicitud
            $idsAEliminar = $request->input('ids');

            DB::table('pst_2.dbo.registro_planilla_pst')->whereIn('cod_reg', $idsAEliminar)->delete();

            $idPlanilla = $request->input('idPlanilla');

            $planillaActualizada = DB::table("pst_2.dbo.v_registro_planilla_pst")
                ->where('cod_planilla', $idPlanilla)
                ->select('*')
                ->get();

            $subtotal = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
                ->select(
                    'c.nombre AS corte_final',
                    'cal.nombre AS calidad',
                    DB::raw('SUM(rp.piezas) AS total_piezas'),
                    DB::raw('SUM(rp.kilos) AS total_kilos'),
                    DB::raw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) AS porcentaje_del_total')
                )
                ->join('pst_2.dbo.corte AS c', 'rp.cod_corte_fin', '=', 'c.cod_corte')
                ->join('pst_2.dbo.calidad AS cal', 'rp.cod_calidad', '=', 'cal.cod_cald')
                ->where('rp.cod_planilla', $idPlanilla)
                ->groupBy('c.nombre', 'cal.nombre')
                ->orderByRaw('CAST(ROUND((SUM(rp.kilos) * 100.0 / (SELECT SUM(kilos) FROM pst_2.dbo.registro_planilla_pst WHERE cod_planilla = ' . $idPlanilla . ')), 2) AS DECIMAL(10,2)) DESC')
                ->orderBy('cal.nombre', 'asc')
                ->orderBy('c.nombre', 'asc')
                ->get();

            $total = DB::table('pst_2.dbo.registro_planilla_pst AS rp')
                ->select(
                    DB::raw("' ' as corte_final"),
                    DB::raw("'Total' as calidad"),
                    DB::raw('SUM(rp.piezas) AS total_piezas'),
                    DB::raw('SUM(rp.kilos) AS total_kilos'),
                    DB::raw('100.0 AS porcentaje_del_total')
                )
                ->where('rp.cod_planilla', $idPlanilla)
                ->get();


            return response()->json(['success' => true, 'message' => 'Registros eliminados correctamente', 'planilla' => $planillaActualizada, 'subtotal' => $subtotal, 'total' => $total]);
        }


        return response()->json(['success' => false, 'message' => 'No se proporcionaron IDs para eliminar']);
    }

    public function guardarTiempoMuerto(Request $request)
    {
        try {
            DB::table('pst_2.dbo.tiempos_muertos')->insert([
                'cod_planilla' => $request->input('idPlanilla'),
                'cod_departamento' => $request->input('cod_departamento'),
                'causa' => $request->input('causa'),
                'hora_inicio' => $request->input('hora_inicio'),
                'hora_termino' => $request->input('hora_termino'),
                'duracion_minutos' => $request->input('duracion_minutos')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tiempo muerto registrado correctamente'
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el tiempo muerto',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function obtenerTiemposMuertos($idPlanilla)
    {
        try {
            \Log::info("Obteniendo tiempos muertos para planilla: " . $idPlanilla);

            $tiemposMuertos = DB::table('pst_2.dbo.tiempos_muertos as tm')
                ->leftJoin('pst_2.dbo.departamentos as d', 'd.cod_departamento', '=', 'tm.cod_departamento')
                ->where('tm.cod_planilla', $idPlanilla)
                ->select('tm.*', 'd.nombre as departamento')
                ->get();

            \Log::info("Tiempos muertos encontrados: ", ['count' => $tiemposMuertos->count(), 'data' => $tiemposMuertos]);

            return response()->json([
                'success' => true,
                'tiemposMuertos' => $tiemposMuertos
            ]);
        } catch (Exception $e) {
            \Log::error("Error al obtener tiempos muertos: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    public function eliminarTiempoMuerto($id)
    {
        try {
            DB::table('pst_2.dbo.tiempos_muertos')
                ->where('cod_tiempo_muerto', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tiempo muerto eliminado correctamente'
            ]);
        } catch (QueryException $e) {
            \Log::error('Error al eliminar tiempo muerto:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el tiempo muerto',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function obtenerDepartamentos()
    {
        try {
            \Log::info("Obteniendo departamentos"); // Debug

            $departamentos = DB::table('pst_2.dbo.departamentos')
                ->where('activo', 1)
                ->select('cod_departamento', 'nombre')
                ->get();

            \Log::info("Departamentos encontrados:", ['count' => $departamentos->count()]); // Debug

            return response()->json([
                'success' => true,
                'departamentos' => $departamentos
            ]);
        } catch (\Exception $e) {
            \Log::error("Error al obtener departamentos: " . $e->getMessage()); // Debug
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los departamentos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}


