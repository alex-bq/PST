<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MisInformesController extends Controller
{
    public function index()
    {
        $user_id = session('user.cod_usuario');

        $informesPendientes = DB::select("
            SELECT
                p.fec_turno,
                t.CodTurno as turno,
                t.NomTurno as Nomturno,
                COUNT(DISTINCT p.cod_planilla) as cantidad_planillas,
                CONCAT(u.nombre, ' ', u.apellido) as jefe_turno,
                SUM(dp.kilos_entrega) as total_kilos_entrega,
                SUM(dp.kilos_recepcion) as total_kilos_recepcion
            FROM pst_2.dbo.planillas_pst p
            JOIN bdsystem.dbo.turno t ON p.cod_turno = t.CodTurno
            JOIN pst_2.dbo.usuarios_pst u ON p.cod_jefe_turno = u.cod_usuario
            JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
            LEFT JOIN pst_2.dbo.informes_turno i ON p.fec_turno = i.fecha_turno 
                AND p.cod_turno = i.cod_turno
            WHERE u.cod_usuario = ? 
                AND p.guardado = 1 
                AND i.cod_informe IS NULL
            GROUP BY 
                p.fec_turno,
                t.CodTurno,
                t.NomTurno,
                u.nombre,
                u.apellido
            ORDER BY p.fec_turno DESC, t.CodTurno",
            [$user_id]
        );

        // // Agregar estos dd() para depuración
        // dd([
        //     'user_id' => $user_id,
        //     'informesPendientes' => $informesPendientes,
        //     'sql' => DB::getQueryLog() // Necesitas habilitar DB::enableQueryLog() al inicio
        // ]);

        // Informes ya creados
        $informesCreados = DB::select("
            SELECT
                i.fecha_turno as fec_turno,
                t.CodTurno as turno,
                t.NomTurno as Nomturno,
                i.cod_informe,
                CONCAT(u.nombre, ' ', u.apellido) as jefe_turno,
                SUM(d.kilos_entrega) as total_kilos_entrega,
                SUM(d.kilos_recepcion) as total_kilos_recepcion
            FROM pst_2.dbo.informes_turno i
            JOIN bdsystem.dbo.turno t ON i.cod_turno = t.CodTurno
            JOIN pst_2.dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
            JOIN pst_2.dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
            WHERE i.cod_jefe_turno = ? 
                AND i.estado = 1
            GROUP BY 
                i.fecha_turno,
                t.CodTurno,
                t.NomTurno,
                i.cod_informe,
                u.nombre,
                u.apellido
            ORDER BY i.fecha_turno DESC, t.CodTurno",
            [$user_id]
        );

        return view('informes.mis-informes', compact('informesPendientes', 'informesCreados'));
    }
}
