<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller

/**
 * @OA\Post(
 *     path="/login",
 *     summary="Iniciar sesión",
 *     description="Permite a un usuario autenticarse con su email y contraseña. Devuelve un token en caso de éxito.",
 *     tags={"Inicio de sesión"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="MiContraseña123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Inicio de sesión exitoso",
 *         @OA\JsonContent(
 *             @OA\Property(property="acceso", type="string", example="Ok"),
 *             @OA\Property(property="error", type="string", example=""),
 *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."),
 *             @OA\Property(property="idUsuario", type="integer", example=1),
 *             @OA\Property(property="nombreUsuario", type="string", example="Juan Pérez")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Credenciales incorrectas",
 *         @OA\JsonContent(
 *             @OA\Property(property="acceso", type="string", example=""),
 *             @OA\Property(property="token", type="string", example=""),
 *             @OA\Property(property="error", type="string", example="No existe el usuario o la contraseña es incorrecta"),
 *             @OA\Property(property="idUsuario", type="integer", example=0),
 *             @OA\Property(property="nombreUsuario", type="string", example="")
 *         )
 *     )
 * )
 */

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
