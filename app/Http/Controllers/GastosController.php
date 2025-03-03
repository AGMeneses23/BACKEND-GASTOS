<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GastosController extends Controller
{

    //LISTAR TODOS LOS GASTOS DEL USUARIO
    /**
     * @OA\Get(
     *     path="/gastos/lista",
     *     summary="Obtener lista de gastos del usuario autenticado",
     *     tags={"Gastos"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de gastos obtenida con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Listado de gastos obtenido exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="id_users", type="integer", example=1),
     *                     @OA\Property(property="id_categoria", type="integer", example=1),
     *                     @OA\Property(property="fecha", type="string", format="date", example="2025-01-19"),
     *                     @OA\Property(property="monto", type="number", format="float", example=5000.00),
     *                     @OA\Property(property="descripcion", type="string", example="Hola, esto es una descripción"),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function indexApi()
    {
        //OBTENER LOS GASTOS DEL USUARIO AUTENTICADO
        $gastosApi = Gasto::where('id_users', Auth::id())
            ->with('categoria') //CARGAR LA RELACIÓN 'categoria' PARA INCLUIR EL NOMBRE
            ->get();

        //REGISTRAR LAS ACCIONES EN EL LOG
        $usuario = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' listó todos los gastos usando el método GET.');

        //RETORNAR LA RESPUESTA CON LOS GASTOS
        return response()->json(['message' => 'Listado de gastos obtenido exitosamente', 'data' => $gastosApi], 200);
    }

    //OBTENER UN GASTO ESPECÍFICO
    public function showApi($id)
    {

        //BUSCA EL GASTO POR ID Y VERIFICA QUE PERTENECE AL USUARIO AUTENTICADO
        $gasto = Gasto::where('id_users', Auth::id())->find($id);

        if (!$gasto) {
            return response()->json(['message' => 'Gasto no encontrado'], 404);
        }

        //SE REGISTRA EN EL LOG
        $usuario = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' visualizó el gasto con ID ' . $id . ' usando el método GET.');

        //REGRESA RESPUESTA
        return response()->json(['message' => 'Gasto encontrado', 'data' => $gasto], 200);
    }

    //CREA UN GASTO
    /**
     * @OA\Post(
     *     path="/gastos/guardar",
     *     summary="Crear un nuevo gasto",
     *     tags={"Gastos"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_categoria","fecha","monto","descripcion"},
     *             @OA\Property(property="id_categoria", type="integer", example=1),
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-01-19"),
     *             @OA\Property(property="monto", type="number", format="float", example=5000.00),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del gasto")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Gasto creado exitosamente")
     * )
     */
    public function createApi(Request $request)
    {
        //CREA AL NUEVO USUARIO
        $gasto = new Gasto();
        $gasto->id_users = Auth::id();
        $gasto->id_categoria = $request->input('id_categoria');
        $gasto->fecha = $request->input('fecha');
        $gasto->monto = $request->input('monto');
        $gasto->descripcion = $request->input('descripcion');
        $gasto->save();

        //REGISTRAR LA ACCIÓN EN EL LOG
        $usuario  = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' creó un nuevo gasto con monto ' . $request->input('monto') . ' usando el método POST.');

        //REGRESA RESPUESTA
        return response()->json(['message' => 'Gasto creado exitosamente', 'data' => $gasto], 201);
    }

    //ACTUALIZA UN GASTO
    /**
     * @OA\Put(
     *     path="/gastos/editar/{id}",
     *     summary="Actualizar un gasto existente",
     *     tags={"Gastos"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del gasto a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_categoria","fecha","monto","descripcion"},
     *             @OA\Property(property="id_categoria", type="integer", example=1),
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-01-19"),
     *             @OA\Property(property="monto", type="number", format="float", example=4500.00),
     *             @OA\Property(property="descripcion", type="string", example="Gasto actualizado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gasto actualizado con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Gasto actualizado con éxito"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="id_users", type="integer", example=1),
     *                 @OA\Property(property="id_categoria", type="integer", example=1),
     *                 @OA\Property(property="fecha", type="string", format="date", example="2025-01-19"),
     *                 @OA\Property(property="monto", type="number", format="float", example=4500.00),
     *                 @OA\Property(property="descripcion", type="string", example="Gasto actualizado correctamente"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Gasto no encontrado")
     * )
     */
    public function editApi(Request $request, $id)
    {
        //BUSCA EL GASTO POR ID
        $gasto = Gasto::where('id_users', Auth::id())->find($id);

        if (!$gasto) {
            return response()->json(['message' => 'Gasto no encontrado'], 404);
        }

        //ACTUALIZAR LOS CAMPOS
        $gasto->id_categoria = $request->input('id_categoria');
        $gasto->fecha = $request->input('fecha');
        $gasto->monto = $request->input('monto');
        $gasto->descripcion = $request->input('descripcion');
        $gasto->save();

        //REGISTRAR LA ACCIÓN EN EL LOG
        $usuario = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' actualizó el gasto con ID ' . $id . ' usando el método PUT.');

        //RETORNAR LA RESPUESTA CON EL GASTO ACTUALIZADO
        return response()->json(['message' => 'Gasto actualizado exitosamente', 'data' => $gasto], 200);
    }

    //ELIMINAR UN GASTO
    /**
     * @OA\Delete(
     *     path="/gastos/eliminar/{id}",
     *     summary="Eliminar un gasto",
     *     tags={"Gastos"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del gasto a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Gasto eliminado exitosamente"),
     *     @OA\Response(response=404, description="Gasto no encontrado")
     * )
     */
    public function eliminarApi($id)
    {
        //BUSCAR EL GASTO POR ID
        $gasto = Gasto::where('id_users', Auth::id())->find($id);

        if (!$gasto) {
            return response()->json(['message' => 'Gasto no encontrado'], 404);
        }

        //ELIMINAR EL GASTO
        $gasto->delete();

        //REGISTAR LA ACCIÓN EN EL LOG
        $usuario = Auth::user()->name;
        Log::channel('activity')->info('Usuario ' . $usuario . ' eliminó el gasto con ID ' . $id . ' usando el método DELETE.');

        //RETORNAR RESPUESTA CON MENSAJE DE ÉXITO
        return response()->json(['message' => 'Gasto eliminado exitosamente'], 200);
    }
}
