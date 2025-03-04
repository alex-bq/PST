<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session('user')) {
            return redirect('/main');
        }

        return view('auth.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('pass', 'usuario');

        $user = DB::table('pst.dbo.v_data_usuario')
            ->select('*')
            ->where('usuario', $credentials['usuario'])
            ->where('pass', $credentials['pass'])
            ->where('activo', 1)
            ->first();

        if ($user) {
            session([
                'user' => [
                    'cod_usuario' => $user->cod_usuario,
                    'usuario' => $user->usuario,
                    'nombre' => $user->nombre,
                    'cod_rol' => $user->cod_rol,
                    'rol' => $user->rol,
                ]
            ]);

            return redirect()->route('main');
        }

        return redirect()->route('login')->with('error', 'Credenciales incorrectas');
    }
    public function showContraForm()
    {
        if (!session('user')) {
            return redirect('/login');
        }

        return view('auth.cambiar-contra');
    }

    public function cambiarContra(Request $request)
    {
        $credentials = $request->only('current_password', 'new_password', 'confirm_password');

        $user = DB::table('pst.dbo.v_data_usuario')
            ->select('*')
            ->where('usuario', session('user.usuario'))
            ->where('pass', $credentials['current_password'])
            ->where('activo', 1)
            ->first();


        if ($user) {
            if ($credentials['new_password'] !== $credentials['confirm_password']) {
                return redirect()->back()->with('error', 'Las contraseñas no coinciden');
            }

            DB::table('pst.dbo.v_data_usuario')
                ->where('usuario', session('user.usuario'))
                ->update(['pass' => $credentials['new_password']]);

            return redirect()->route('main');
        }

        return redirect()->back()->with('error', 'Contraseña Incorrecta');
    }

    public function logout()
    {
        session()->forget('user');

        return redirect('/login')->with('message', 'Has cerrado sesión exitosamente.');
    }

}
