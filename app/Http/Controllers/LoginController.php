<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('app')->plainTextToken;

            //Registro de log para inicio de sesión exitoso
            Log::channel('activity')->info('Usuario ' . $user->name . ' inició sesión exitosamente.');

            $arr = array(
                'acceso' => "Ok",
                'error' => "",
                'token' => $token,
                'idUsuario' => $user->id,
                'nombreUsuario' => $user->name
            );

            return json_encode($arr);
        } else {

            // Registro de log para intento de inicio de sesión fallido
            Log::channel('activity')->warning('Intento de inicio de sesión fallido para el email: ' . $request->email);

            $arr = array(
                'acceso' => "",
                'token' => "",
                'error' => "No existe el usuario o la contraseña es incorrecta",
                'idUsuario' => 0,
                'nombreUsuario' => ''
            );
            return json_encode($arr);
        }
    }
}
