<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GastosController extends Controller
{

    //LISTAR TODOS LOS GASTOS DEL USUARIO
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
