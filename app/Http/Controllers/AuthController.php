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
        $credentials = $request->only('nombre_usuario', 'pass');

        $user = DB::table('usuarios_pst')
            ->where('nombre_usuario', $credentials['nombre_usuario'])
            ->where('pass', $credentials['pass'])
            ->first();

        if ($user) {
            // Autenticación exitosa
            session(['user' => [
                'id' => $user->cod_usuario,
                'nombre_usuario' => $user->nombre_usuario,
                'rol_supervisor' => $user->rol_supervisor,
                'rol_admin' => $user->rol_admin,
            ]]);

            return redirect()->route('inicio');
        }

        // Autenticación fallida
        return redirect()->route('login')->with('error', 'Credenciales incorrectas');
    }

    public function logout()
{
    // Obtener datos del usuario antes de cerrar sesión (si es necesario)
    $userData = session('user');

    // Limpiar la sesión
    session()->forget('user');

    return redirect('/login')->with('message', 'Has cerrado sesión exitosamente.');
}

}
