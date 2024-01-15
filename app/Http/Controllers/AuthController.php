<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('usuario', 'pass');

        $user = DB::table('pst.dbo.v_data_usuario')
            ->where('usuario', $credentials['usuario'])
            ->where('pass', $credentials['pass'])
            ->first();

        if ($user) {
            // Autenticación exitosa
            session(['user' => [
                'cod_usuario' => $user->cod_usuario,
                'usuario' => $user->usuario,
                'nombre' => $user->nombre,
                'cod_rol'=> $user->cod_rol,
                'rol' => $user->rol,
            ]]);

            return redirect()->route('inicio');
        }

        // Autenticación fallida
        return redirect()->route('login')->with('error', 'Credenciales incorrectas');
    }

    public function logout()
{


    // Limpiar la sesión
    session()->forget('user');

    return redirect('/login')->with('message', 'Has cerrado sesión exitosamente.');
}

}
