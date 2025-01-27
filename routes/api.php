<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\GastosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Ruta de registro de nuevos usuarios
Route::post('/register', [RegisterController::class, 'register']);

//Ruta para el logueo CON FACEBOOK
Route::post('/login-facebook', [FacebookAuthController::class, 'loginWithFacebook']);

//Ruta de logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

//Ruta de login
Route::post('login', [LoginController::class, 'login']);

//Ruta de recuperacion de contraseña
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);

//RUTAS PARA EL CONTROLADOR CATEGORÍAS
Route::middleware('auth:sanctum')->get('/categorias/lista', [CategoriasController::class, 'indexApi']);
Route::middleware('auth:sanctum')->get('/categorias/{id}', [CategoriasController::class, 'catApi']);
Route::middleware('auth:sanctum')->put('/categorias/editar/{id}', [CategoriasController::class, 'editApi']);
Route::middleware('auth:sanctum')->delete('/categorias/eliminar/{id}', [CategoriasController::class, 'eliminarApi']);
Route::middleware('auth:sanctum')->post('/categorias/guardar', [CategoriasController::class, 'createApi']);

//RUTAS PARA EL CONTROLADOR DE GASTOS
Route::middleware('auth:sanctum')->get('/gastos/lista', [GastosController::class, 'indexApi']);
Route::middleware('auth:sanctum')->get('/gastos/{id}', [GastosController::class, 'showApi']);
Route::middleware('auth:sanctum')->put('/gastos/editar/{id}', [GastosController::class, 'editApi']);
Route::middleware('auth:sanctum')->delete('/gastos/eliminar/{id}', [GastosController::class, 'eliminarApi']);
Route::middleware('auth:sanctum')->post('/gastos/guardar', [GastosController::class, 'createApi']);
