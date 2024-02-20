<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class adminController extends Controller
{

    public function mCorte()
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }

        $cortes = DB::table('pst.dbo.corte')->select('*')->get();


        return view('admin.mantencion.corte', compact('cortes'));
    }
    public function guardarCorte(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $corteExistente = DB::table('pst.dbo.corte')
            ->where('nombre', $request->nombre)
            ->first();

        if ($corteExistente) {
            return response()->json(['message' => 'El corte ya existe en la base de datos.', 'error' => 1]);
        }
        DB::table('pst.dbo.corte')->insert([
            'nombre' => $request->nombre,
            'activo' => $request->activo,
        ]);
        return response()->json(['message' => 'Corte guardado exitosamente.', 'error' => 0]);
    }

    public function editarCorte(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        try {
            $affectedRows = DB::table('pst.dbo.corte')
                ->where('cod_corte', $request->cod_corte)
                ->update([
                    'nombre' => $request->nombre,
                    'activo' => $request->activo,
                ]);

            if ($affectedRows > 0) {
                $corteActualizado = DB::table('pst.dbo.corte')->where('cod_corte', $request->id)->first();
                return response()->json(['message' => 'Corte actualizado exitosamente.', 'corte' => $corteActualizado, 'error' => 0]);
            } else {
                return response()->json(['message' => 'No se encontró el corte a actualizar.', 'error' => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el corte: ' . $e->getMessage(), 'error' => 1]);
        }
    }


    public function mCalidad()
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $calidades = DB::table('pst.dbo.calidad')->select('*')->get();
        return view('admin.mantencion.calidad', compact('calidades'));
    }

    public function guardarCalidad(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $calidadExistente = DB::table('pst.dbo.calidad')
            ->where('nombre', $request->nombre)
            ->first();

        if ($calidadExistente) {
            return response()->json(['message' => 'La calidad ya existe en la base de datos.', 'error' => 1]);
        }
        DB::table('pst.dbo.calidad')->insert([
            'nombre' => $request->nombre,
            'activo' => $request->activo,
        ]);
        return response()->json(['message' => 'Calidad guardada exitosamente.', 'error' => 0]);
    }

    public function editarCalidad(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        try {
            $affectedRows = DB::table('pst.dbo.calidad')
                ->where('cod_cald', $request->cod_cald)
                ->update([
                    'nombre' => $request->nombre,
                    'activo' => $request->activo,
                ]);

            if ($affectedRows > 0) {
                $calidadActualizada = DB::table('pst.dbo.calidad')->where('cod_cald', $request->id)->first();
                return response()->json(['message' => 'Calidad actualizada exitosamente.', 'calidad' => $calidadActualizada, 'error' => 0]);
            } else {
                return response()->json(['message' => 'No se encontró la calidad a actualizar.', 'error' => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar la calidad: ' . $e->getMessage(), 'error' => 1]);
        }
    }

    public function mDestino()
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $destinos = DB::table('pst.dbo.destino')->select('*')->get();


        return view('admin.mantencion.destino', compact('destinos'));
    }
    public function guardarDestino(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $destinoExistente = DB::table('pst.dbo.destino')
            ->where('nombre', $request->nombre)
            ->first();

        if ($destinoExistente) {
            return response()->json(['message' => 'El destino ya existe en la base de datos.', 'error' => 1]);
        }
        DB::table('pst.dbo.destino')->insert([
            'nombre' => $request->nombre,
            'activo' => $request->activo,
        ]);
        return response()->json(['message' => 'Destino guardado exitosamente.', 'error' => 0]);
    }

    public function editarDestino(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        try {
            $affectedRows = DB::table('pst.dbo.destino')
                ->where('cod_destino', $request->cod_destino)
                ->update([
                    'nombre' => $request->nombre,
                    'activo' => $request->activo,
                ]);

            if ($affectedRows > 0) {
                $destinoActualizado = DB::table('pst.dbo.destino')->where('cod_destino', $request->id)->first();
                return response()->json(['message' => 'Destino actualizado exitosamente.', 'destino' => $destinoActualizado, 'error' => 0]);
            } else {
                return response()->json(['message' => 'No se encontró el destino a actualizar.', 'error' => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el destino: ' . $e->getMessage(), 'error' => 1]);
        }
    }
    public function mCalibre()
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $calibres = DB::table('pst.dbo.calibre')->select('*')->get();


        return view('admin.mantencion.calibre', compact('calibres'));
    }
    public function guardarCalibre(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $calibreExistente = DB::table('pst.dbo.calibre')
            ->where('nombre', $request->nombre)
            ->first();

        if ($calibreExistente) {
            return response()->json(['message' => 'El calibre ya existe en la base de datos.', 'error' => 1]);
        }
        DB::table('pst.dbo.calibre')->insert([
            'nombre' => $request->nombre,
            'activo' => $request->activo,
        ]);
        return response()->json(['message' => 'Calibre guardado exitosamente.', 'error' => 0]);
    }

    public function editarCalibre(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        try {
            $affectedRows = DB::table('pst.dbo.calibre')
                ->where('cod_calib', $request->cod_calib)
                ->update([
                    'nombre' => $request->nombre,
                    'activo' => $request->activo,
                ]);

            if ($affectedRows > 0) {
                $calibreActualizado = DB::table('pst.dbo.calibre')->where('cod_calib', $request->id)->first();
                return response()->json(['message' => 'Calibre actualizado exitosamente.', 'calibre' => $calibreActualizado, 'error' => 0]);
            } else {
                return response()->json(['message' => 'No se encontró el calibre a actualizar.', 'error' => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el calibre: ' . $e->getMessage(), 'error' => 1]);
        }
    }
    public function mSala()
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $salas = DB::table('pst.dbo.sala')->select('*')->get();
        return view('admin.mantencion.sala', compact('salas'));
    }

    public function guardarSala(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $salaExistente = DB::table('pst.dbo.sala')
            ->where('nombre', $request->nombre)
            ->first();

        if ($salaExistente) {
            return response()->json(['message' => 'La sala ya existe en la base de datos.', 'error' => 1]);
        }
        DB::table('pst.dbo.sala')->insert([
            'nombre' => $request->nombre,
            'activo' => $request->activo,
        ]);
        return response()->json(['message' => 'Sala guardada exitosamente.', 'error' => 0]);
    }

    public function editarSala(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        try {
            $affectedRows = DB::table('pst.dbo.sala')
                ->where('cod_sala', $request->cod_sala)
                ->update([
                    'nombre' => $request->nombre,
                    'activo' => $request->activo,
                ]);

            if ($affectedRows > 0) {
                $salaActualizada = DB::table('pst.dbo.sala')->where('cod_sala', $request->id)->first();
                return response()->json(['message' => 'Sala actualizada exitosamente.', 'sala' => $salaActualizada, 'error' => 0]);
            } else {
                return response()->json(['message' => 'No se encontró la sala a actualizar.', 'error' => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar la calidad: ' . $e->getMessage(), 'error' => 1]);
        }
    }










    public function mUsuario()
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $usuarios = DB::table('pst.dbo.v_data_usuario')->select('*')->orderBy('cod_rol')->get();
        $roles = DB::table('pst.dbo.roles')->select('*')->orderBy('nombre_rol')->get();


        return view('admin.mantencion.usuario', compact('usuarios', 'roles'));
    }
    public function guardarUsuario(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        $usuarioExistente = DB::table('pst.dbo.usuarios_pst')
            ->where('usuario', $request->usuario)
            ->first();

        if ($usuarioExistente) {
            return response()->json(['message' => 'El usuario ya existe en la base de datos.', 'error' => 1]);
        }
        DB::table('pst.dbo.usuarios_pst')->insert([
            'usuario' => $request->usuario,
            'pass' => $request->contra,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'cod_rol' => $request->rol,
            'activo' => $request->activo,
        ]);
        return response()->json(['message' => 'Usuario guardado exitosamente.', 'error' => 0]);
    }

    public function editarUsuario(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/inicio');
        }
        try {
            $affectedRows = DB::table('pst.dbo.usuarios_pst')
                ->where('cod_usuario', $request->cod_usuario)
                ->update([
                    'usuario' => $request->usuario,
                    'pass' => $request->contra,
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'cod_rol' => $request->rol,
                    'activo' => $request->activo,
                ]);

            if ($affectedRows > 0) {
                $usuarioActualizado = DB::table('pst.dbo.usuarios_pst')->where('cod_usuario', $request->id)->first();
                return response()->json(['message' => 'Usuario actualizado exitosamente.', 'corte' => $usuarioActualizado, 'error' => 0]);
            } else {
                return response()->json(['message' => 'No se encontró el usuario a actualizar.', 'error' => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el usuario: ' . $e->getMessage(), 'error' => 1]);
        }
    }



}
