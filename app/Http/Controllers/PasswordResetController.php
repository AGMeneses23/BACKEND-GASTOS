<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller

/**
 * @OA\Post(
 *     path="/password/reset",
 *     summary="Restablecer la contraseña de un usuario",
 *     description="Permite a un usuario restablecer su contraseña proporcionando su correo electrónico y la nueva contraseña.",
 *     tags={"Recuperación de contraseña"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password", "password_confirmation"},
 *             @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="NuevaContraseña123"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="NuevaContraseña123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contraseña actualizada exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Contraseña actualizada exitosamente.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error de validación",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="object", example={"email": {"El campo email es obligatorio."}, "password": {"El campo password es obligatorio."}})
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usuario no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Usuario no encontrado.")
 *         )
 *     )
 * )
 */

{
    public function resetPassword(Request $request)
    {
        //Se validadn los datos de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        //Buscar al usuario por el correo
        $user = User::where('email', $request->email)->first();

        //Verifica si el usuario existe y actualiza la contraseña
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message' => "Constraseña actualizada exitosamente."], 200);
        } else {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        }
    }
}
