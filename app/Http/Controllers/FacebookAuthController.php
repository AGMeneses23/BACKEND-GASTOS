<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookAuthController extends Controller
{
    public function loginWithFacebook(Request $request)
    {
        //validar datos de entrada
        $request->validate([
            'accessToken' => 'required|string',
            'userID' => 'required|string',
        ]);

        //verificar el token con la API de facebook
        $fbResponse = Http::get("https://graph.facebook.com/me", [
            'fields' => 'id,name,email',
            'access_token' => $request->accessToken,
        ]);


        if ($fbResponse->failed()) {
            return response()->json([
                'error' => 'No se pudo validar el token de Facebook.',
            ], 401);
        }

        $fbUser = $fbResponse->json();

        //verificar si el usuario ya está registrado
        $user = User::where('email', $fbUser['email'])->first();

        if (!$user) {
            //Si no esta registrado, crearlo
            $user = User::create([
                'name' => $fbUser['name'],
                'email' => $fbUser['email'],
                'password' => bcrypt('facebook_default_password'),
                'rol' => 'user',
            ]);

            //Log de creación de usuario
            Log::channel('activity')->info('Nuevo usuario registrado con Facebook: ' . $user->name . ' (' . $user->email . ')');
        }

        //Registrar el inicio de sesión en los logs
        Log::channel('activity')->info('Usuario ' . $user->name . ' inició sesión con Facebook.');
        
        //Genberar un token para la sesión
        $token = $user->createToken('auth_token')->plainTextToken;

        //Devolver respuesta del cliente
        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
}
