<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
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
