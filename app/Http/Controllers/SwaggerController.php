<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Gestión de Gastos",
 *     description="Documentación de la API para el manejo de la aplicación de gastos personales",
 *     @OA\Contact(
 *         email="savemoney@gmail.com",
 *         name="Save Money - GastosApp"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerController extends Controller
{
    //
}
