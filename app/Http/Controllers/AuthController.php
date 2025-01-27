<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        $user = $request->user();
        Log::channel('activity')->info('Usuario ' . $user->name . ' cerro la sesión');

        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}
